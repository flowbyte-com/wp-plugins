=== 808 AI Haimaker Provider ===
Contributors: flowbyte
Tags: ai, haimaker, chatbot, provider, wordpress-ai-client
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Haimaker provider plugin for WordPress AI Client — OpenAI-compatible API.

== Description ==

This plugin adds Haimaker as a provider for the WordPress AI Client, enabling WordPress to connect to Haimaker's API using the standard AI Client interface.

**Requires WordPress 7.0 or later** with the WordPress AI Client (built into WordPress 7+).

**Features:**

* OpenAI-compatible API
* Standard WordPress AI Client integration
* API key authentication

== Installation ==

1. Upload the `flowbyte-haimaker-connector` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** screen in WordPress
3. Configure your Haimaker API key in **Settings > AI** or via the AI Client settings

**Requirements:**

* WordPress 7.0 or later
* Haimaker API key from [haimaker.ai](https://haimaker.ai/)

== Frequently Asked Questions ==

= Does this work standalone? =

No. This is a provider plugin that requires the WordPress AI Client to be active. It will not do anything if WordPress 7.0+ is not installed with the AI Client enabled.

= Which models are supported? =

Haimaker Auto and other Haimaker models.

= How do I get an API key? =

Sign up at [haimaker.ai](https://haimaker.ai/) to get an API key.

== Changelog ==

= 1.0.0 =
* Initial release — Haimaker provider for WordPress AI Client