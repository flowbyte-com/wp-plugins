# 808 AI Plugin — SPEC

**Version:** 1.9.7
**Location:** `wp-plugins/808-ai.zip`
**Extracted:** `808-ai/` inside zip

---

## Overview

808 AI is a lightweight WordPress chatbot powered by MiniMax Token Plan (direct Anthropic-compatible API). It provides a floating bubble or inline chat widget, a settings page, and an optional FSE Design Sidebar for the WordPress Site Editor.

---

## Features

### Chat Widget

- **[808_chat]** shortcode — renders inline chat on any page/post
- **Floating bubble** — always-on floating button (bottom-right), toggles open/closed
- **Markdown rendering** — AI replies rendered via `marked.js` + `DOMPurify`
- **Honeypot anti-bot** — hidden field that bots fill, humans never see
- **Per-IP rate limiting** — configurable messages per minute and per day (WordPress transients)
- **Conversation history** — sent with each request, up to 2,000 chars per message

### REST API

- `POST /wp-json/808-ai/v1/chat` — public, honeypot-protected, rate-limited
  - Request: `{ "message": "...", "history": [{role, content}] }`
  - Response: `{ "reply": "..." }` or `{ "error": "..." }`

### Settings

- API key (MiniMax Token Plan, stored as WP option)
- System prompt (textarea, overridable per-page via post meta `808_sys_prompt`)
- Floating bubble toggle + open-by-default option
- Knowledge base (appended to system prompt as reference document)
- Rate limits: per-minute and per-day (per IP)

### FSE Design Sidebar

- Appears in WordPress Site Editor → Design → 808 AI Designer
- Reads site templates, block patterns, global styles, theme.json
- Sends context to MiniMax with user design prompt
- Proposes JSON change plan (theme_json / block_html / template)
- Apply button writes changes via REST or filesystem API

---

## File Structure

```
808-ai.zip extracts to:
├── 808-ai.php          ← main plugin (settings, REST, widget, styles)
├── readme.txt          ← WordPress.org readme
├── icon.svg            ← lobster emoji SVG
├── icon-128x128.png
├── icon-256x256.png
├── src/
│   └── Fse/
│       ├── autoload.php
│       ├── FseDesignSidebar.php       ← REST routes + AI orchestration
│       ├── SiteContextReader.php      ← reads templates/patterns/styles/theme.json
│       └── BlockMarkupGenerator.php   ← applies JSON change to theme.json or templates
└── assets/js/
    ├── marked.min.js
    ├── dompurify.min.js
    ├── fse-design-sidebar.js
    └── fse-design-sidebar.asset.php
```

---

## Settings Options

| Option | Default | Description |
|--------|---------|-------------|
| `808_api_key` | — | MiniMax API key |
| `808_sys_prompt` | "You are 808, a helpful AI assistant." | Global system prompt |
| `808_model` | `MiniMax-M2.7` | Model (hardcoded, token plan uses single model) |
| `808_float_enabled` | `0` | Enable floating bubble |
| `808_float_open` | `0` | Open bubble by default on page load |
| `808_knowledge_base` | `""` | Reference document appended to system prompt |
| `808_rate_per_minute` | `5` | Max messages per IP per minute |
| `808_rate_per_day` | `50` | Max messages per IP per day |
| `808_connector` | `direct` | Route (always `direct` in current version) |

---

## REST Endpoints

| Route | Methods | Auth | Description |
|-------|----------|------|-------------|
| `/808-ai/v1/chat` | POST | public + honeypot | Chat messages |
| `/808-ai/v1/design` | POST | `edit_theme_options` | AI design proposals |
| `/808-ai/v1/design/apply` | POST | `edit_theme_options` | Apply design change |

---

## Text Domain

`flowbyte_808` (underscores, no dashes)

---

## Dependencies

- `marked@9.0.0` — Markdown parsing (bundled, local)
- `dompurify@3.0.0` — HTML sanitization (bundled, local)
- WP7 AI Client (`WordPress\AiClient\AiClient`) — not used by 808 direct API, but FSE sidebar loads independently
