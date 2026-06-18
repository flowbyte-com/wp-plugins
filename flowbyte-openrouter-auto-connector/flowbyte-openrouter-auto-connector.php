<?php

/**
 * Plugin Name: 808 AI OpenRouter Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-openrouter-auto-connector
 * Description: OpenRouter provider for WordPress AI Client. Supports all OpenRouter models (GPT-4o, Claude, Gemini, Llama, DeepSeek, Mistral, and 100+ more).
 * Version: 1.0.0
 * Author: Flowbyte
 * Author URI: https://flowbyte.com
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain: flowbyte_808_openrouter
 * Requires at least: 7.0
 * Requires PHP: 7.4
 */

declare(strict_types=1);

namespace FlowByte\EightOpenRouterProvider;

use WordPress\AiClient\AiClient;
use FlowByte\EightOpenRouterProvider\Provider\OpenRouterProvider;

if (!defined('ABSPATH')) {
    return;
}

require_once __DIR__ . '/src/autoload.php';

/**
 * Registers the 808 AI OpenRouter Provider with the WordPress AI Client.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_provider(): void
{
    if (!class_exists(AiClient::class)) {
        return;
    }

    $registry = AiClient::defaultRegistry();

    if ($registry->hasProvider(OpenRouterProvider::class)) {
        return;
    }

    $registry->registerProvider(OpenRouterProvider::class);
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);