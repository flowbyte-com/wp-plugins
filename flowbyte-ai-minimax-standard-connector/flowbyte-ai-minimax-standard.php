<?php

/**
 * Plugin Name: 808 AI MiniMax Standard Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-ai-minimax-standard
 * Description: Standard MiniMax provider for WordPress AI Client — OpenAI-compatible API endpoint.
 * Version: 1.0.0
 * Author: Flowbyte
 * Author URI: https://flowbyte.com
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain: flowbyte_808_minimax_standard
 * Requires at least: 7.0
 * Requires PHP: 7.4
 */

declare(strict_types=1);

namespace FlowByte\EightMinimaxStandard;

use WordPress\AiClient\AiClient;
use FlowByte\EightMinimaxStandard\Provider\MinimaxStandardProvider;

if (!defined('ABSPATH')) {
    return;
}

require_once __DIR__ . '/src/autoload.php';

function register_provider(): void
{
    if (!class_exists(AiClient::class)) {
        return;
    }

    $registry = AiClient::defaultRegistry();

    if ($registry->hasProvider(MinimaxStandardProvider::class)) {
        return;
    }

    $registry->registerProvider(MinimaxStandardProvider::class);
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);