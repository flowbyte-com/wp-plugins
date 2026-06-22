<?php

/**
 * Plugin Name: 808 AI MiniMax Token Plan Provider
 * Plugin URI: https://github.com/flowbyte/flowbyte-ai-minimax-token
 * Description: MiniMax Token Plan provider for WordPress AI Client — M2.7 model, same cost for all models via token plan.
 * Version: 1.0.3
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

/**
 * Adds the MiniMax Token Plan provider/model to the AI service preferred-models list.
 *
 * Without this filter, the WordPress AI plugin's `get_preferred_models_for_text_generation()`
 * hardcodes only [anthropic, google, openai]. With no credentials set for those, every
 * text-generation call fails with "Title generation failed…" even though this plugin is
 * fully configured and connected.
 *
 * @since 1.0.1
 *
 * @param array<int, array{string, string}> $preferred_models Existing preferred-models list.
 * @return array<int, array{string, string}> Filtered list with MiniMax prepended.
 */
function add_to_preferred_text_models(array $preferred_models): array
{
    // Prepend so it's tried first when its model is configured.
    array_unshift(
        $preferred_models,
        array('flowbyte-minimax-token', 'MiniMax-M2.7')
    );
    return $preferred_models;
}
add_filter('wpai_preferred_text_models', __NAMESPACE__ . '\\add_to_preferred_text_models');
