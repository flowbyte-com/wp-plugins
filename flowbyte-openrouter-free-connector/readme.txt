=== 808 AI OpenRouter Free Provider ===
Contributors: flowbyte
Tags: ai, openrouter, free, chatbot, provider, wordpress-ai-client, llama, mistral, qwen, deepseek
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.0
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

== Installation ==

1. Upload the `flowbyte-openrouter-free-connector` folder to `/wp-content/plugins/`, or install through the WordPress plugin directory
2. Activate the plugin through the **Plugins** screen in WordPress
3. Sign up at [openrouter.ai](https://openrouter.ai/) (free, no payment required) and create an API key
4. Go to **Settings → Connectors**, find "808 AI (OpenRouter Free)", and paste your key

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

No. The free tier works without a payment method. If you later want access to paid models (GPT-4o, Claude, etc.), install the companion plugin [808 AI OpenRouter](https://github.com/flowbyte/flowbyte-openrouter-auto-connector) instead.

= Is my API key safe? =

Your API key is stored encrypted in `wp_options` by the WordPress Connectors API — the same storage used by Anthropic, Google, and OpenAI's built-in provider plugins. It is never exposed to JavaScript on the front end and never leaves the server except as a Bearer token on outbound requests to OpenRouter.

= Why does it ask for an OpenRouter key instead of working without one? =

OpenRouter aggregates models from many providers and requires authentication to call any of them, including free ones. The sign-up takes a minute; the key never expires.

== Changelog ==

= 1.1.0 =
* Live model discovery — replace static model list with `/api/v1/models` query filtered for `:free` suffix. Model list refreshes daily via 24h cache. Plugin now self-heals as OpenRouter rotates its free tier.
* Declare `inputModalities` and `outputModalities` on each model so the AI Client's text-generation discovery works.
* Multimodal free models now surface their actual input modality set (text, image, video, audio as available) instead of being treated as text-only.

= 1.0.0 =
* Initial release — OpenRouter Free provider for WordPress AI Client.