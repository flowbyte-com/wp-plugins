<?php

/**
 * Plugin Name: 808 AI MiniMax Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-ai-minimax
 * Description: MiniMax provider for WordPress AI Client — M2.7 token plan model.
 * Version: 1.0.0
 * Author: Flowbyte
 * Author URI: https://flowbyte.com
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain: flowbyte_808_minimax
 * Requires at least: 7.0
 * Requires PHP: 7.4
 */

declare(strict_types=1);

namespace FlowByte\EightMinimax;

use WordPress\AiClient\AiClient;
use FlowByte\EightMinimax\Provider\MinimaxProvider;

if (!defined('ABSPATH')) {
    return;
}

require_once __DIR__ . '/src/autoload.php';

/**
 * Registers the 808 AI MiniMax Provider with the WordPress AI Client.
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

    if ($registry->hasProvider(MinimaxProvider::class)) {
        return;
    }

    $registry->registerProvider(MinimaxProvider::class);
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);