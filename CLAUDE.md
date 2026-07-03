# Plugin: BuddyPress Member Reviews

> **READ FIRST:** [`audit/manifest.json`](audit/manifest.json) is the canonical inventory —
> 7 AJAX handlers, 2 admin-post handlers, 6 admin pages, 8 settings option keys, 2 shortcodes,
> 1 CPT (`review`) + 1 taxonomy (`review_category`), 2 widgets, 1 cron hook, 0 REST/0 DB tables,
> 31 fired hooks. **Use this before grepping.** See also
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
- **Version**: `3.8.0`  | **Dev branch**: `3.7.1`
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
  (`includes/admin/class-bupr-admin-panel.php:128-152`). **PRIMARY settings page.**

### Settings options read/written
- `bupr_admin_general_options` (array, 20 subkeys) — `BUPR_Admin_Panel::OPTION_GENERAL`,
  registered `includes/admin/class-bupr-admin-panel.php:177-184`.
- `bupr_admin_settings` (array — criteria) — `OPTION_CRITERIA`, registered `:186-192`.
- `bupr_admin_display_options` (array — labels + star color) — `OPTION_DISPLAY`, registered `:194-200`.
- Standalone: `bupr_plugin_version`, `bupr_current_batch`, `bupr_top_members_cache_ver`
  (cache salt for the top-members shortcode transient), `bupr_member_type_criteria` (Pro-owned;
  free neither reads nor writes since the orphaned member-types tab template was deleted in 3.8.0),
  `bupr_pro_options.review_mode` (Pro-persisted), transient `_bupr_activation_redirect`.
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

## Open findings
All three findings from the 2026-06-05 wppqa baseline are **fixed in the 3.8.0 cycle**
(verified in code, see `AUDIT-VERDICT.md`):
1. ~~`bupr_update_review` missing ownership check~~ — fixed: ownership/`edit_others_posts`
   gate at `includes/bupr-ajax.php:710-728`, mirroring `bupr_edit_review`.
2. ~~Cron scheduled with conflicting intervals~~ — fixed: single guarded `daily` registration
   on `init` (`bupr_schedule_review_recalculation`, `includes/bupr-general-functions.php`).
3. ~~Native `alert()` in bupr-front.js / missing wbcom-themes-page.php~~ — fixed: alerts
   replaced by the toast system; the OLD wrapper (and its broken template include) was removed
   in the card-panel migration.

Still open (minor, from `AUDIT-VERDICT.md` 2026-07-03):
- `wp_enqueue_style()` passes literal `false` for `$media` in 7 call sites
  (`admin/bupr-admin.php:97`; `includes/bupr-scripts.php:104,105,255,258,260,268`).
- Hardcoded non-translatable in-flight label `'Approving..'` in `admin/assets/js/bupr-admin.js`.
- PHPStan has no WordPress stubs configured (baseline noise, not a code signal).

(False positive, already triaged: nonce-no-cap on `wp_allow_bupr_my_member` bupr-ajax.php:156 —
public/nopriv submission endpoint, nonce-only by design.)

## Build
`grunt` (gruntfile.js) — JS/CSS minification + RTL generation. `npm install` first.

## Recent changes
| Date | Type | Description | Files |
|------|------|-------------|-------|
| 2026-07-03 | bug-fix | Approve Review admin AJAX success check now branches on the stable `response.data.code` (`review-approved-successfully`) instead of the translated message — approve flow no longer hangs on non-English installs | admin/assets/js/bupr-admin.js (+min) |
| 2026-07-03 | a11y | `:focus-visible` outline restored for `.bupr-reply-textarea`; label[for]/aria-label added to unlabeled settings inputs (email subject/message fields, Multiple Criteria switch, dynamic criteria rows + JS row generator) | assets/css/bupr-front.css (+min/rtl), admin/tab-templates/bupr-setting-{general,criteria}-tab.php, admin/assets/js/bupr-admin.js |
| 2026-07-03 | refactor | Deleted orphaned `bupr-member-types-criteria-tab.php` (dead template with unregistered options.php form); manifest/FILE_INDEX synced | admin/tab-templates/, audit/manifest.json, docs/FILE_INDEX.md |
| 2026-07-03 | perf | `[bupr_display_top_members]` CAST-ordered usermeta query cached in 15-min versioned transient; `bupr_flush_top_members_cache()` bumps the salt on recalc/status-transition/delete | includes/bupr-shortcodes.php, includes/bupr-general-functions.php |
| 2026-07-03 | docs | Version sync (package.json + .pot → 3.8.0); CLAUDE.md wrapper classification updated to NEW card-panel shell; open findings refreshed | package.json, languages/bp-member-reviews.pot, CLAUDE.md |
| 2026-07-03 | bug-fix | Dark mode rebuilt as token pattern: dark triggers (`html[data-bx-mode="dark"]`, `auto`+`prefers-color-scheme`, legacy `body.buddyx-dark-theme`/`.dark-mode`/`.dark-scheme`) set `--bupr-*` tokens; component rules written once with light fallbacks. Covers rows, edit/flag/success modals, owner replies, report buttons, notices, pagination. | assets/css/bupr-front.css (+min/rtl) |
| 2026-06-05 | docs | Onboarding: regenerated audit/ (manifest, FEATURE_AUDIT, CODE_FLOWS, graph, wppqa baseline) + READ-FIRST CLAUDE.md | audit/, CLAUDE.md |
