=== 808 AI OpenRouter Free Provider ===
Contributors: flowbyte
Tags: ai, openrouter, free, chatbot, provider, wordpress-ai-client
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

OpenRouter Free provider plugin for WordPress AI Client — access to free models including Llama, Mistral, and OpenChat.

== Description ==

This plugin adds OpenRouter Free as a provider for the WordPress AI Client, enabling WordPress to connect to OpenRouter's free tier using the standard AI Client interface.

**Requires WordPress 7.0 or later** with the WordPress AI Client (built into WordPress 7+).

**Features:**

* Access to free models via OpenRouter
* Standard WordPress AI Client integration
* API key authentication

**Supported models include:** Llama 3 8B Instruct, Mistral 7B Instruct, OpenChat 7B.

== Installation ==

1. Upload the `flowbyte-openrouter-free-connector` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** screen in WordPress
3. Configure your OpenRouter API key in **Settings > AI** or via the AI Client settings

**Requirements:**

* WordPress 7.0 or later
* OpenRouter API key from [openrouter.ai](https://openrouter.ai/)

== Frequently Asked Questions ==

= Does this work standalone? =

No. This is a provider plugin that requires the WordPress AI Client to be active. It will not do anything if WordPress 7.0+ is not installed with the AI Client enabled.

= Which models are supported? =

Free models: Llama 3 8B Instruct, Mistral 7B Instruct, and OpenChat 7B.

= How do I get an API key? =

Sign up at [openrouter.ai](https://openrouter.ai/) to get an API key. The free tier does not require payment.

== Changelog ==

= 1.0.0 =
* Initial release — OpenRouter Free provider for WordPress AI Client