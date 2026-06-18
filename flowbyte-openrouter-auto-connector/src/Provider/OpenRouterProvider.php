<?php

declare(strict_types=1);

namespace FlowByte\EightOpenRouterProvider\Provider;

use WordPress\AiClient\Common\Exception\RuntimeException;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiProvider;
use WordPress\AiClient\Providers\ApiBasedImplementation\ListModelsApiBasedProviderAvailability;
use WordPress\AiClient\Providers\Contracts\ModelMetadataDirectoryInterface;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\DTO\ProviderMetadata;
use WordPress\AiClient\Providers\Enums\ProviderTypeEnum;
use WordPress\AiClient\Providers\Http\Enums\RequestAuthenticationMethod;
use WordPress\AiClient\Providers\Models\Contracts\ModelInterface;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use FlowByte\EightOpenRouterProvider\Metadata\OpenRouterModelMetadataDirectory;
use FlowByte\EightOpenRouterProvider\Models\OpenRouterTextGenerationModel;

/**
 * Class for the 808 AI OpenRouter provider.
 *
 * @since 1.0.0
 */
class OpenRouterProvider extends AbstractApiProvider
{
    protected static function baseUrl(): string
    {
        return 'https://openrouter.ai/api/v1';
    }

    protected static function createModel(
        ModelMetadata $modelMetadata,
        ProviderMetadata $providerMetadata
    ): ModelInterface {
        $capabilities = $modelMetadata->getSupportedCapabilities();
        foreach ($capabilities as $capability) {
            if ($capability->isTextGeneration()) {
                return new OpenRouterTextGenerationModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException(
            'Unsupported model capabilities: ' . implode(', ', $capabilities)
        );
    }

    protected static function createProviderMetadata(): ProviderMetadata
    {
        return new ProviderMetadata(
            'flowbyte-openrouter-auto',
            '808 AI (OpenRouter)',
            ProviderTypeEnum::cloud(),
            'https://openrouter.ai/',
            RequestAuthenticationMethod::apiKey(),
            'OpenRouter — access to GPT-4o, Claude, Gemini, Llama, DeepSeek, Mistral, and 100+ other models.'
        );
    }

    protected static function createProviderAvailability(): ProviderAvailabilityInterface
    {
        return new ListModelsApiBasedProviderAvailability(
            static::modelMetadataDirectory()
        );
    }

    protected static function createModelMetadataDirectory(): ModelMetadataDirectoryInterface
    {
        return new OpenRouterModelMetadataDirectory();
    }
}