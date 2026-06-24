<?php

/**
 * Plugin Name: 808 AI OpenRouter Free Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-openrouter-free-connector
 * Description: OpenRouter Free provider for WordPress AI Client — free models including Llama, Mistral, and OpenChat.
 * Version: 1.1.0
 * Author: Flowbyte
 * Author URI: https://flowbyte.com
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain: flowbyte_808_openrouter_free
 * Requires at least: 7.0
 * Requires PHP: 7.4
 */

declare(strict_types=1);

namespace FlowByte\EightOpenRouterFree;

use WordPress\AiClient\AiClient;
use FlowByte\EightOpenRouterFree\Provider\OpenRouterFreeProvider;

if (!defined('ABSPATH')) {
    return;
}

require_once __DIR__ . '/src/autoload.php';

/**
 * Registers the 808 AI OpenRouter Free Provider with the WordPress AI Client.
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

    if ($registry->hasProvider(OpenRouterFreeProvider::class)) {
        return;
    }

    $registry->registerProvider(OpenRouterFreeProvider::class);
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);