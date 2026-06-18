=== 808 AI MiniMax Provider ===
Contributors: flowbyte
Tags: ai, minimax, chatbot, provider, wordpress-ai-client
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

MiniMax provider plugin for WordPress AI Client — M2.7 token plan model.

== Description ==

This plugin adds MiniMax as a provider for the WordPress AI Client, enabling WordPress to connect to MiniMax's API using the standard AI Client interface.

**Requires WordPress 7.0 or later** with the WordPress AI Client (built into WordPress 7+).

**Features:**

* MiniMax M2.7 model (Token Plan billing)
* Token plan — same cost regardless of model
* Standard WordPress AI Client integration
* API key authentication

== Installation ==

1. Upload the `flowbyte-ai-minimax` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** screen in WordPress
3. Configure your MiniMax API key in **Settings > AI** or via the AI Client settings

**Requirements:**

* WordPress 7.0 or later
* MiniMax API key from [minimax.io](https://www.minimax.io/)

== Frequently Asked Questions ==

= Does this work standalone? =

No. This is a provider plugin that requires the WordPress AI Client to be active. It will not do anything if WordPress 7.0+ is not installed with the AI Client enabled.

= What is the Token Plan? =

MiniMax's token plan billing means the same cost regardless of which model you use. The M2.7 model is the primary model for this provider.

= How do I get an API key? =

Sign up at [minimax.io](https://www.minimax.io/) to get an API key.

== Changelog ==

= 1.0.0 =
* Initial release — MiniMax provider for WordPress AI Client