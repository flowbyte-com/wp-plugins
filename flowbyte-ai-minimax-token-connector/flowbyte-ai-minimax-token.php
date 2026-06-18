<?php

/**
 * Plugin Name: 808 AI MiniMax Token Plan Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-ai-minimax-token
 * Description: MiniMax Token Plan provider for WordPress AI Client — M2.7 model, same cost for all models via token plan.
 * Version: 1.0.0
 * Author: Flowbyte
 * Author URI: https://flowbyte.com
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain: flowbyte_808_minimax_token
 * Requires at least: 7.0
 * Requires PHP: 7.4
 */

declare(strict_types=1);

namespace FlowByte\EightMinimaxToken;

use WordPress\AiClient\AiClient;
use FlowByte\EightMinimaxToken\Provider\MinimaxTokenProvider;

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

    if ($registry->hasProvider(MinimaxTokenProvider::class)) {
        return;
    }

    $registry->registerProvider(MinimaxTokenProvider::class);
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);