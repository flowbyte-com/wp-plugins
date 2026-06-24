=== 808 AI MiniMax Token Plan Provider ===
Contributors: flowbyte
Tags: ai, minimax, chatbot, provider, wordpress-ai-client, token-plan
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

MiniMax Token Plan provider for WordPress AI Client — flat-rate token plan billing across MiniMax-M3, MiniMax-M2.7, and MiniMax-M2.7-highspeed models.

== Description ==

This plugin adds MiniMax Token Plan as a provider for the WordPress AI Client. MiniMax's token plan offers flat-rate billing — same cost regardless of which model you use — and that cost is per token rather than per call, which makes it competitive with metered API pricing for sites that do real volume.

The plugin exposes three models: **MiniMax-M3** (current generation, ~1.5s P50 latency), **MiniMax-M2.7** (previous generation), and **MiniMax-M2.7-highspeed** (low-latency variant). All three use the same `/v1/chat/completions` endpoint with Bearer-token authentication.

**Requires WordPress 7.0 or later** with the WordPress AI Client (built into WordPress core).

**Features:**

* Three MiniMax models via a single API key
* Reasoning-aware response handling (chain-of-thought stripped from final output)
* Standard WordPress AI Client integration via the WordPress Connectors API
* API key authentication

== Installation ==

1. Upload the `flowbyte-ai-minimax-token` folder to `/wp-content/plugins/`, or install through the WordPress plugin directory
2. Activate the plugin through the **Plugins** screen in WordPress
3. Sign up at [minimax.io](https://www.minimax.io/) and create a token-plan API key
4. Go to **Settings → Connectors**, find "808 AI (MiniMax Token Plan)", and paste your key
5. **Approve this plugin to use the connector** — WordPress 7.0's AI plugin gates outbound calls per-caller. Visit **Tools → Connector Approvals** and toggle on "808 AI MiniMax Token Plan Provider" for the "808 AI (MiniMax Token Plan)" connector. Without this step, features like AI-generated titles, excerpts, and the chat interface will silently fail with a generic network error.

== Troubleshooting ==

= My AI feature returns 'failed' or 'network error' even though the connector says Connected =

You probably need to approve this plugin to use the connector. Go to **Tools → Connector Approvals** and make sure "808 AI MiniMax Token Plan Provider" is toggled on for the "808 AI (MiniMax Token Plan)" connector. This is a one-time per-site admin step introduced in WordPress 7.0.

= I see only one model in the connector settings =

By default the AI plugin picks the first model exposed by the provider — MiniMax-M3. To use M2.7 or M2.7-highspeed, toggle on "Model selection" in the AI plugin settings and pick one explicitly.

== Changelog ==

= 1.0.4 =
* Documented the Connector Approval step (WordPress 7.0 AI plugin's per-caller permission layer).
* Corrected description: three models exposed (MiniMax-M3 default, MiniMax-M2.7, MiniMax-M2.7-highspeed) and actual endpoint is `/v1/chat/completions` not `/anthropic/v1`.

= 1.0.3 =
* Declared inputModalities + outputModalities on every model so AI Client's text-generation discovery finds them.
* Added MiniMax-M3 as default model (M2.7 reasoning mode was exceeding client timeouts).
* Strip inline `<think>...</think>` reasoning wrapper from response content.
* Removed LSP-incompatible prepareMessagesParam override that caused a fatal Declaration error on PHP 8.x.

= 1.0.0 =
* Initial release — MiniMax Token Plan provider for WordPress AI Client.