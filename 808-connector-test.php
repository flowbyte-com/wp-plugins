<?php
/**
 * Plugin Name: 808 Connector Test
 * Description: Quick test harness for flowbyte connectors via WP7 AI Client.
 * Version: 1.0.1
 */

add_action('rest_api_init', function () {
    register_rest_route('connector-test', '/run', [
        'methods' => 'POST',
        'callback' => 'connector_test_run',
        'permission_callback' => '__return_true',
    ]);
});

function connector_test_run(WP_REST_Request $request) {
    $connector_id = $request->get_param('connector_id') ?: 'flowbyte-deepseek';
    $prompt_text  = $request->get_param('prompt') ?: 'Say hello in one word.';

    $registry = \WordPress\AiClient\AiClient::defaultRegistry();

    if (!$registry->hasProvider($connector_id)) {
        return new WP_REST_Response([
            'error' => "Provider '$connector_id' not found in registry.",
            'available' => $registry->getRegisteredProviderIds(),
        ], 400);
    }

    // Check key source and configuration
    $setting_name = 'connectors_ai_' . str_replace('-', '_', $connector_id) . '_api_key';
    $stored_key   = get_option($setting_name, '');
    $key_source   = 'none';
    if (getenv(strtoupper(str_replace('-', '_', $connector_id)) . '_API_KEY')) {
        $key_source = 'env';
    } elseif (!empty($stored_key)) {
        $key_source = 'database (connectors)';
    }

    $configured = $registry->isProviderConfigured($connector_id);

    // Set auth from stored key if needed
    if (!empty($stored_key)) {
        try {
            $auth = new \WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication($stored_key);
            $registry->setProviderRequestAuthentication($connector_id, $auth);
        } catch (Throwable $e) {
            // ignore
        }
    }

    // List models for this provider
    $available_models = [];
    try {
        $provider_class = $registry->getProviderClassName($connector_id);
        $dir = $provider_class::modelMetadataDirectory();
        foreach ($dir->listModelMetadata() as $m) {
            $available_models[] = [
                'id' => $m->getId(),
                'name' => $m->getName(),
                'capabilities' => array_map(fn($c) => $c->value, $m->getSupportedCapabilities()),
            ];
        }
    } catch (Throwable $e) {
        $available_models = 'error: ' . $e->getMessage();
    }

    // Try PromptBuilder approach
    $gen_error = null;
    $gen_result = null;
    try {
        $builder = \WordPress\AiClient\AiClient::prompt($prompt_text);
        $builder->usingProvider($connector_id);
        $result = $builder->generateTextResult();
        $gen_result = $result->toText();
    } catch (Throwable $e) {
        $gen_error = $e->getMessage();
    }

    // Try direct model call with proper Message objects
    $direct_result = null;
    $direct_error = null;
    try {
        $model = $registry->getProviderModel($connector_id, 'deepseek-chat');
        $message = new \WordPress\AiClient\Messages\DTO\Message(
            \WordPress\AiClient\Messages\Enums\MessageRoleEnum::user(),
            [new \WordPress\AiClient\Messages\DTO\MessagePart($prompt_text)]
        );
        $result = $model->generateTextResult([$message]);
        $direct_result = $result->toText();
    } catch (Throwable $e) {
        $direct_error = $e->getMessage() . ' | ' . basename($e->getFile()) . ':' . $e->getLine();
    }

    return new WP_REST_Response([
        'connector' => $connector_id,
        'setting_name' => $setting_name,
        'key_source' => $key_source,
        'key_length' => $stored_key ? strlen($stored_key) : 0,
        'is_configured' => $configured,
        'available_models' => $available_models,
        'promptbuilder_result' => $gen_result,
        'promptbuilder_error' => $gen_error,
        'direct_result' => $direct_result,
        'direct_error' => $direct_error,
    ], 200);
}
