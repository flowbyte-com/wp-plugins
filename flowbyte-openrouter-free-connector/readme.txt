=== 808 AI OpenRouter Free Provider ===
Contributors: flowbyte
Tags: ai, openrouter, free, chatbot, provider, wordpress-ai-client, llama, mistral, qwen, deepseek
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Free AI provider plugin for WordPress AI Client — connects to OpenRouter's free tier with no per-token cost. Live model discovery means new free models show up automatically.

== Description ==

**Free AI for every WordPress site.** This plugin connects your WordPress 7.0+ site to OpenRouter's free tier — a rotating selection of models from Meta, Mistral, Google, NVIDIA, Qwen, OpenAI, and others. No payment required, no per-token billing.

Unlike other provider plugins that ship with a fixed list of models (and silently rot as providers rotate their free tiers), this plugin queries OpenRouter's `/api/v1/models` endpoint on initialization and surfaces every model whose ID ends with `:free` — today, tomorrow, and whenever the free lineup changes. Results are cached for 24 hours.

**Requires WordPress 7.0 or later** with the WordPress AI Client (built into WordPress core).

**Features:**

* Access to all of OpenRouter's currently-free models — Llama, Mistral, Gemma, NVIDIA Nemotron, Qwen, GPT-OSS, and more
* Live model discovery — the model list updates itself as OpenRouter rotates the free tier
* Multimodal support — free models with image or video input capability are surfaced for features that need them
* Standard WordPress AI Client integration via the WordPress Connectors API
* Bearer-token authentication with the same flow as other first-party AI providers

**Why free?** Because per-token billing for AI features is a tax on small sites, and the WordPress ecosystem deserves an option that doesn't gouge. The OpenRouter free tier is a workable answer; this plugin is the plumbing that makes it usable in WordPress without you writing code.

== External services ==

This plugin connects to the [OpenRouter API](https://openrouter.ai/) to discover and call AI models. Specifically, it makes the following outbound calls:

* `GET https://openrouter.ai/api/v1/models` — to enumerate the currently-available models and filter for the free tier. No content is sent; the call is read-only.
* `POST https://openrouter.ai/api/v1/chat/completions` — to generate text, image, or multimodal responses when a WordPress AI Client feature requests them. The request body contains the prompt your site is sending.

Authentication is via Bearer token using your OpenRouter API key, which is stored encrypted in your site's `wp_options` table by the WordPress Connectors API.

Using this plugin requires an OpenRouter API key, which can be obtained by creating a free account on the [OpenRouter](https://openrouter.ai/) website. Create an account and generate an API key at https://openrouter.ai/settings/keys. OpenRouter's [terms of service](https://openrouter.ai/terms) and [privacy policy](https://openrouter.ai/privacy) apply to any data you send through the plugin.

== Installation ==

1. Upload the `flowbyte-openrouter-free-connector` folder to `/wp-content/plugins/`, or install through the WordPress plugin directory
2. Activate the plugin through the **Plugins** screen in WordPress
3. Sign up at [openrouter.ai](https://openrouter.ai/) (free, no payment required) and create an API key
4. Go to **Settings → Connectors**, find "808 AI (OpenRouter Free)", and paste your key
5. **Approve this plugin to use the connector** — WordPress 7.0's AI plugin gates outbound calls per-caller. Visit **Tools → Connector Approvals** and toggle on "808 AI OpenRouter Free Provider" for the "808 AI (OpenRouter Free)" connector. Without this step, features like AI-generated titles, excerpts, and the chat interface will silently fail with a generic network error.

== Frequently Asked Questions ==

= Does this work standalone? =

No. This is a provider plugin that requires the WordPress AI Client, which is built into WordPress 7.0+. It will not do anything useful on WordPress 6.x or earlier.

= Is the OpenRouter free tier really free? =

Yes. OpenRouter's `:free` models don't require payment or a credit card. Sign-up is enough. Free models do have per-minute rate limits and may be temporarily unavailable under heavy load; the plugin surfaces whatever is currently free at the time of the request.

= Which models are supported? =

Whatever OpenRouter is currently offering for free. As of this writing that includes Llama 3.x, Mistral, Gemma 4, NVIDIA Nemotron (multiple sizes), Qwen3, GPT-OSS, Cohere North, and others. The full list is fetched live — see OpenRouter's [models page](https://openrouter.ai/models) for the current free lineup.

= How often does the model list refresh? =

Every 24 hours. The plugin caches the model list and refreshes it lazily on the next request after the cache expires.

= Do I need a paid OpenRouter account? =

No. The free tier works without a payment method. If you later want access to paid models (GPT-4o, Claude, etc.), install the companion plugin `808 AI OpenRouter Provider` instead.

= Is my API key safe? =

Your API key is stored encrypted in `wp_options` by the WordPress Connectors API — the same storage used by Anthropic, Google, and OpenAI's built-in provider plugins. It is never exposed to JavaScript on the front end and never leaves the server except as a Bearer token on outbound requests to OpenRouter.

= Why does it ask for an OpenRouter key instead of working without one? =

OpenRouter aggregates models from many providers and requires authentication to call any of them, including free ones. The sign-up takes a minute; the key never expires.

== Screenshots ==

1. Settings → Connectors with the OpenRouter Free connector configured and showing as **Connected**, alongside the built-in Anthropic, Google, and OpenAI connectors.
2. AI Request Logs showing successful completions routing through `openrouter/free` and other free models.

== Changelog ==

= 1.1.1 =
* Added the "External services" disclosure required by the WordPress plugin directory guidelines (describes the API endpoints the plugin calls, the data sent, and the linked terms of service).
* Tightened wording in description and FAQ to lead with "free, just works" instead of competitive framing.

= 1.1.0 =
* Live model discovery — replace static model list with `/api/v1/models` query filtered for `:free` suffix. Model list refreshes daily via 24h cache. Plugin now self-heals as OpenRouter rotates its free tier.
* Declare `inputModalities` and `outputModalities` on each model so the AI Client's text-generation discovery works.
* Multimodal free models now surface their actual input modality set (text, image, video, audio as available) instead of being treated as text-only.

= 1.0.0 =
* Initial release — OpenRouter Free provider for WordPress AI Client.