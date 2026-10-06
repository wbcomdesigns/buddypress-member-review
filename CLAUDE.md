# Plugin: BuddyPress Member Reviews

> **READ FIRST:** [`audit/manifest.json`](audit/manifest.json) is the canonical inventory —
> 6 AJAX handlers, 2 admin-post handlers, 2 admin pages, 11 settings/option keys, 1 shortcode,
> 1 CPT (`review`) + 1 taxonomy (`review_category`), 2 meta boxes, 2 widgets, 2 cron hooks,
> 0 blocks/0 REST/0 DB tables, 62 fired hooks. **Use this before grepping.** See also
> [`audit/FEATURE_AUDIT.md`](audit/FEATURE_AUDIT.md), [`audit/CODE_FLOWS.md`](audit/CODE_FLOWS.md),
> [`audit/wppqa-baseline-2026-06-05/SUMMARY.md`](audit/wppqa-baseline-2026-06-05/SUMMARY.md).
> Interactive graph: `cd audit && python3 -m http.server 8765` -> http://localhost:8765/graph.html
> Refresh via `/wp-plugin-onboard --refresh` after non-trivial changes.

> **Development conventions:** follow the `/wp-plugin-development` skill for ALL work here —
> the 16 critical admin rules, Part 6 Admin UI patterns, design tokens, escaping/security rules,
> and dev hygiene (no native alert/confirm, Lucide icons, 3-breakpoint discipline). The admin
> was migrated to the card-panel shell in the 3.8.0 cycle (see below).

## Quick reference
- **Main file**: `buddypress-member-review.php`
- **Version**: `3.8.1`  | **Dev branch**: `3.8.1`
- **Namespace**: none — procedural functions + prefixed classes (`BUPR_*`, `Wbcom_Admin_Settings`)
- **Prefix**: `bupr_`  | **Text domain**: `bp-member-reviews`  | **WP.org slug**: `bp-user-profile-reviews`
- **Extends**: null. **Extended by**: `buddypress-member-review-pro` (separate repo, hook-only coupling).
- **Requires**: BuddyPress >= 6.0.0 OR BuddyBoss >= 1.8.0, members component active.

## Admin-UI wrapper classification: **NEW (card-panel shell)**
Migrated from the OLD `Wbcom_Admin_Settings` wrapper to the card-panel shell in the 3.8.0
cycle (commit `b4bcd2a`). Evidence:
- `includes/admin/views/shell.php` present (NEW-pattern marker); `admin/wbcom/` removed.
- `includes/admin/class-bupr-admin-panel.php` (`BUPR_Admin_Panel`) owns menu, settings
  registration, asset enqueue (`assets/css/admin.css` + `assets/js/admin.js`), and page render.
- Views: `includes/admin/views/{shell,hub,overview,settings-general,settings-criteria,settings-display,settings-pro-features}.php`;
  the settings views `require` the legacy field markup from `admin/tab-templates/*.php`.

### Admin menu registration
- Top-level **`wbcomplugins`** ("WB Plugins") — shared hub; the first Wbcom plugin to load
  creates it (`BUPR_Admin_Panel::add_menu`, guarded by `$GLOBALS['admin_page_hooks']`).
- **`bp-member-review-settings`** ("Member Reviews") — `add_submenu_page` of `wbcomplugins`,
  `manage_options`, `BUPR_Admin_Panel::render_page`
  (`includes/admin/class-bupr-admin-panel.php:155-162`). **PRIMARY settings page.**
- Tabs (`BUPR_Admin_Panel::get_tabs`): Overview, General, Criteria, Display, Discover.

### REMOVED: the `bupr-pro-features` ("Add-ons") tab - read before shipping Pro
Removed 2026-07-17 (owner override of the 2026-07-17 "keep the mount" call: QA was right that an
unreleased Pro means free users see an empty tab). **This tab carried Pro's mount point**, so
restoring a surface for Pro needs BOTH halves back:
1. The tab entry in `get_tabs()` + its `$view_map` entry in `render_page()`. Slug
   `bupr-pro-features` is a **public contract** (`?tab=` URLs + the tab map Pro filters via
   `bupr_admin_settings_tabs`) - restore it verbatim, do not rename.
2. The deleted view + template that fired `do_action( 'bupr_admin_settings_pro_content' )`:
   `includes/admin/views/settings-pro-features.php` + `admin/tab-templates/bupr-setting-pro-features-tab.php`.
   Pro hooks that action **unconditionally** and renders **form contents only**, so the
   do_action() must sit inside `wbcom-tab-content` > `wbcom-admin-title-section` >
   `wbcom-admin-option-wrap` or Pro's fields render unstyled outside the card chrome.

The full restore checklist lives as a comment at the removal site in `get_tabs()`; the hook entry
in `audit/manifest.json` is flagged `REMOVED_2026-07-17_NOT_FIRED`. A stale
`?tab=bupr-pro-features` URL now falls back to Overview (verified, no fatal). Nothing in free
consumed the hook, and the `wbcom-*` wrapper CSS is shared with the 3 live settings tabs, so
nothing was orphaned.

### Settings options read/written
- `bupr_admin_general_options` (array, 19 subkeys) — `BUPR_Admin_Panel::OPTION_GENERAL`,
  registered `includes/admin/class-bupr-admin-panel.php:189-196`.
- `bupr_admin_settings` (array — criteria) — `OPTION_CRITERIA`, registered `:197-204`.
- `bupr_admin_display_options` (array — labels + star color) — `OPTION_DISPLAY`, registered `:205-212`.
- Standalone: `bupr_plugin_version`, `bupr_current_batch`, `bupr_top_members_cache_ver`
  (cache salt for the top-members shortcode transient), `bupr_meta_repair_v1_done` +
  `bupr_meta_repair_paged` (one-time meta-drift repair gate + cursor),
  transients `_bupr_activation_redirect`
  and `bupr_drift_checked_{user_id}` (24h per-user directory drift-check throttle).
  `bupr_member_type_criteria` is Pro-owned and has **zero** references in free (dropped from
  the manifest in the 2026-07-16 refresh).
- All hydrated into global `$bupr` by `BUPRGlobals` (includes/class-buprglobals.php). Full map: manifest `settings`.

## Key entry points
- Main / bootstrap: `buddypress-member-review.php`
- Globals: `includes/class-buprglobals.php` (global `$bupr`, `bupr_global_settings` filter)
- Admin: `includes/admin/class-bupr-admin-panel.php` (card-panel shell: menu, settings, views)
  + `admin/bupr-admin.php` (CPT list tooling, flag/report admin-post handlers)
- AJAX: `includes/bupr-ajax.php` (class `BUPR_AJAX`)
- BP integration / profile tabs: `includes/bupr-filters.php` (class `BUPR_Custom_Hooks`)
- Shortcodes + front form: `includes/bupr-shortcodes.php`
- Notifications: `includes/bupr-notification.php`
- Helpers + cron + recalc: `includes/bupr-general-functions.php`
- Assets: `includes/bupr-scripts.php`

## Architecture notes
Procedural + thin per-concern classes; no DI/container/REST in free. State = CPT `review`
(author = reviewer, `linked_bp_member` meta = reviewed user) + cached aggregate usermeta
(`bupr_review_count`, `bupr_aggregate_rating`). Pro coupling is hook-only via `bupr_*` filters
(`bupr_global_settings`, `bupr_admin_settings_tabs`, `bupr_after_rating_fields`,
`bupr_before_save_review`, `bupr_aggregate_rating`, `bupr_shortcode_show_stars`, ...).

## Conventions & gotchas
Bugs and their status live in **Basecamp**; the customer-facing history lives in **readme.txt**.
This file is only for how to work in this codebase. Keep it that way - do not add changelog rows.

**Criteria option keys are identifiers, not labels.** `bupr_admin_settings['profile_rating_fields']`
is a map keyed by criterion name, and that same key indexes each review's `profile_star_rating`
meta (matched with `array_key_exists()`). Store keys **untranslated** via
`bupr_get_default_rating_fields()` and translate at render via `bupr_get_criteria_label()`.
Storing a translated key freezes the site in its activation locale forever. The criteria **editor**
deliberately emits the raw key - rendering the translated one would rewrite stored keys on save
under a non-English locale.

**Those two helpers must stay in the main plugin file**, not `includes/bupr-general-functions.php`.
`includes/` loads on `plugins_loaded`, which has already fired when the activation hook runs, so
moving them fatals activation (verified, not theoretical).

**The two review templates disagree about the criteria map's VALUE, on purpose-by-accident.**
`bupr-reviews-tab-template.php` (profile Reviews tab, the main surface) iterates every key and
ignores the value. `bupr-single-review-template.php:79` filters on `'yes'`, but has a
"no matches -> show ALL stored ratings" fallback right below it. Net effect: only a *partial*
`'no'` state can drop a criterion. Do not add a reader that treats `'no'` as "hidden" without
also rendering the per-criterion checkbox - deriving state from a field no template emits is
exactly what broke this before.

**Frontend theme bridge order is `--bx-color-*` -> `--reign-*` -> `--wp--preset--color--*` ->
literal.** Reign 8 removed `--bx-color-*`, so a BuddyX-only chain silently ignores the whole Reign
palette. Only bg/field/border/text/accent are mapped; the presets have no "muted"/"subtle" concept,
so those keep literals deliberately. The full reasoning is commented at the `:root` block in
`assets/css/bupr-front.css` - read it before extending the chain.

**Two admin stylesheets load on the settings screen**: the modern card-panel `assets/css/admin.css`
(which defines the `--bupr-admin-*` tokens, scoped to `.bupr-admin`) and the legacy
`admin/assets/css/bupr-admin.css`. The legacy sheet **also** loads on the `review` CPT screen,
which has **no** `.bupr-admin` wrapper - so every `var(--bupr-admin-*)` there must keep a literal
fallback or the colour vanishes on that screen.

**Never style the settings notice by its core ID.** `#setting-error-settings_updated` is an ID
(1,0,0) and beats `.bupr-admin .notice` (0,2,0) whatever the load order, so an ID rule silently
disables the card-panel styling. Style `.bupr-admin .notice`. Equally, **keep legacy selectors
scoped** - an unscoped `button.notice-dismiss` rule in that sheet restyles core's and other
plugins' notices on every screen it loads on.

**Dark mode is keyed only on explicit signals**, never `prefers-color-scheme` (standing owner
decision: the theme owns the toggle, not the OS). Frontend uses `html[data-bx-mode="dark"]` +
legacy body classes; admin uses `body.is-dark-theme` only (`body.admin-color-dark` was dropped in 3.8.1: WP's "Dark" scheme leaves the content area light, so it produced light-on-light header text). These differ by
necessity - frontend theme variables do not exist in wp-admin. When you tokenise an admin
background, tokenise its **text colour in the same rule**, or it inherits WP's near-black default
onto a now-dark surface.

**Admin AJAX must branch on `response.data.code`,** never the translated message - message-matching
hangs the flow on non-English installs.

**Do not re-minify or reformat the vendored `.min` files.** They are byte-frozen so customer caches
and behaviour do not change; verify with `md5` against HEAD after any vendor work. See "Vendored
libraries" below, including why `select2.min.css` is not actually minified.

## Build
`grunt` (gruntfile.js) — JS/CSS minification + RTL generation. `npm install` first.

## Vendored libraries

WordPress.org requires the human-readable source alongside any minified file we distribute.
Owner rule: **drop what WP core already bundles; ship the unminified source for the rest.**
WP core bundles jQuery/jQuery UI/underscore/backbone/lodash/react — it bundles **neither**
select2 **nor** Font Awesome, so both must ship with source.

**Never re-minify or reformat the `.min` files.** They are byte-frozen so customer caches and
behaviour do not change. Verify with `md5` against git HEAD after any vendor work.

| Lib | Exact version | Shipped files | Refresh URL |
|-----|---------------|---------------|-------------|
| Select2 (JS) | **4.1.0-beta.0** | `admin/assets/js/vendor/select2.min.js` + `select2.js` (source) | `https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/js/select2.js` |
| Select2 (CSS) | **4.1.0-beta.0**, locally themed | `admin/assets/css/vendor/select2.min.css` (**already source — see caveat**) | `https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/css/select2.css` |
| Font Awesome Free | **5.5.0** | `assets/css/fontawesome/all.min.css` + `all.css` (source) + `webfonts/` | `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.5.0/css/all.css` |

### Caveat 1 — `select2.min.css` is NOT minified (misleading filename)
Despite the `.min` name this file is **530 lines, 4-space indented, longest line 210 chars, and
16866 B — larger than the official *minified* build (14966 B)**. It is the official
**unminified** `select2.css` 4.1.0-beta.0, reformatted (closing brace on its own line), plus
**local theme edits**:
- added rule `span.select2.select2-container { width: 100% !important; }`
- multiselect border `#aaa`→`#e3e2ec`, radius `4px`→`8px`; choice bg `#e4e4e4`→`#e8eaf2`,
  border `#cdcdd1`, radius `8px`; focus border `solid black`→`solid #e3e2ec`
- cosmetic colour shortening (`white`→`#fff`, `#eeeeee`→`#eee`)

**It therefore already IS the human-readable source — no sibling `select2.css` is needed, and
shipping the stock one would be actively wrong** (stock has different colours/radius, so it
would not correspond to what we ship). Do **not** "fix" this by downloading `select2.css`.
Renaming it to drop `.min` would change the enqueued URL
(`includes/bupr-scripts.php:309`) and bust caches — left as-is deliberately.

### Caveat 2 — bundled `all.min.css` is FA 5.5.0 **plus two local edits**
`all.min.css` is byte-identical to upstream FA Free 5.5.0 `all.min.css` in **1310 of 1313
rules**. Only the 3 `@font-face` blocks differ, by two deliberate local changes:
1. `../webfonts/` → `webfonts/` — the fonts live *beside* the CSS (2026-07-17 bug-fix row below).
2. `.eot`/`.ttf`/`.svg` sources dropped from each `src:` list — those files are not bundled
   (2026-07-17 perf row); only `.woff2` + `.woff` ship.

`all.css` is upstream `all.css` 5.5.0 with **exactly the same two edits applied to the same 3
`@font-face` blocks** — verified by diffing both against upstream: each differs from its
upstream counterpart only in those blocks. **When refreshing FA, re-apply both edits to
`all.css` and `all.min.css` together, or the source stops corresponding to the min.**

### Caveat 3 — `vendor/` in `.gitignore` must stay root-anchored (`/vendor/`)
A bare `vendor/` also matches `admin/assets/js/vendor/` and `admin/assets/css/vendor/`, which
silently ignores the shipped library sources — the commit looks fine and regresses on next
clone. Keep the leading slash. `copy:dist`'s `!vendor/**` glob is already root-anchored by
grunt's `expand: true` and does **not** drop the asset vendor dirs (verified against the zip).
