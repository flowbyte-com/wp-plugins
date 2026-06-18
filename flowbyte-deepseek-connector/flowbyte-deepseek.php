<?php

/**
 * Plugin Name: 808 AI DeepSeek Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-deepseek
 * Description: DeepSeek provider for WordPress AI Client — DeepSeek Chat and DeepSeek Coder.
 * Version: 1.0.0
 * Author: Flowbyte
 * Author URI: https://flowbyte.com
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain: flowbyte_deepseek
 * Requires at least: 7.0
 * Requires PHP: 7.4
 */

declare(strict_types=1);

namespace FlowByte\EightDeepSeek;

use WordPress\AiClient\AiClient;
use FlowByte\EightDeepSeek\Provider\DeepSeekProvider;

if (!defined('ABSPATH')) {
    return;
}

require_once __DIR__ . '/src/autoload.php';

/**
 * Registers the 808 AI DeepSeek Provider with the WordPress AI Client.
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

    if ($registry->hasProvider(DeepSeekProvider::class)) {
        return;
    }

    $registry->registerProvider(DeepSeekProvider::class);
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);