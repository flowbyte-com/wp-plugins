=== 808 AI DeepSeek Provider ===
Contributors: flowbyte
Tags: ai, deepseek, chatbot, provider, wordpress-ai-client
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

DeepSeek provider plugin for WordPress AI Client — DeepSeek Chat and DeepSeek Coder.

== Description ==

This plugin adds DeepSeek as a provider for the WordPress AI Client, enabling WordPress to connect to DeepSeek's API using the standard AI Client interface.

**Requires WordPress 7.0 or later** with the WordPress AI Client (built into WordPress 7+).

**Features:**

* DeepSeek Chat — general conversation AI
* DeepSeek Coder — code-specialized AI
* Standard WordPress AI Client integration
* API key authentication

== Installation ==

1. Upload the `flowbyte-deepseek` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** screen in WordPress
3. Configure your DeepSeek API key in **Settings > AI** or via the AI Client settings

**Requirements:**

* WordPress 7.0 or later
* DeepSeek API key from [deepseek.com](https://deepseek.com/)

== Frequently Asked Questions ==

= Does this work standalone? =

No. This is a provider plugin that requires the WordPress AI Client to be active. It will not do anything if WordPress 7.0+ is not installed with the AI Client enabled.

= Which models are supported? =

DeepSeek Chat and DeepSeek Coder.

= How do I get an API key? =

Sign up at [deepseek.com](https://deepseek.com/) to get an API key.

== Changelog ==

= 1.0.0 =
* Initial release — DeepSeek provider for WordPress AI Client