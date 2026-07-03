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
> and dev hygiene (no native alert/confirm, Lucide icons, 3-breakpoint discipline). This plugin
> currently uses the OLD admin wrapper and predates several of those conventions (see below).

## Quick reference
- **Main file**: `buddypress-member-review.php`
- **Version**: `3.7.1`  | **Dev branch**: `3.7.1`
- **Namespace**: none — procedural functions + prefixed classes (`BUPR_*`, `Wbcom_Admin_Settings`)
- **Prefix**: `bupr_`  | **Text domain**: `bp-member-reviews`  | **WP.org slug**: `bp-user-profile-reviews`
- **Extends**: null. **Extended by**: `buddypress-member-review-pro` (separate repo, hook-only coupling).
- **Requires**: BuddyPress >= 6.0.0 OR BuddyBoss >= 1.8.0, members component active.

## Admin-UI wrapper classification: **OLD**
This plugin uses the **OLD Wbcom admin wrapper**: `admin/wbcom/wbcom-admin-settings.php`
(class `Wbcom_Admin_Settings`). Evidence:
- `admin/wbcom/wbcom-admin-settings.php` present (the OLD wrapper marker file).
- No `includes/shared-admin/class-wbcom-shared-dashboard.php` (INTERMEDIATE) and no
  `includes/admin/views/shell.php` (NEW).
- Settings page is the legacy tab-based UI: `BUPR_Admin::bupr_plugin_settings_tabs()` renders
  `nav-tab` anchors; tabs registered via `add_settings_section` / `register_setting` and
  shown with `settings_fields()` + `do_settings_sections()`.

### Admin menu registration
- Top-level **`wbcomplugins`** ("WB Plugins") — `add_menu_page`, `manage_options`, icon
  `dashicons-lightbulb`, pos 59. Registered in `admin/bupr-admin.php:193` on `admin_menu`
  (only if `$GLOBALS['admin_page_hooks']['wbcomplugins']` is empty — shared across Wbcom plugins).
- **`bp-member-review-settings`** ("Member Reviews") — `add_submenu_page` of `wbcomplugins`,
  `manage_options`, callback `BUPR_Admin::bupr_admin_options_page`. **PRIMARY settings page.**
  Hook: `admin_menu` (`BUPR_Admin::bupr_add_submenu_page_admin_settings`, `admin/bupr-admin.php:191-197`).
- Wrapper submenus (`Wbcom_Admin_Settings::wbcom_admin_additional_pages`, `admin_menu` pri 999,
  `admin/wbcom/wbcom-admin-settings.php:288`): `wbcom-plugins-page`, `wbcom-themes-page`,
  `wbcom-support-page` — all `manage_options`.

### Settings options read/written
- `bupr_admin_general_options` (array, 20 subkeys) — registered `admin/bupr-admin.php:211`.
- `bupr_admin_settings` (array — criteria) — registered `admin/bupr-admin.php:221`.
- `bupr_admin_display_options` (array — labels + star color) — registered `admin/bupr-admin.php:231`.
- Standalone: `bupr_plugin_version`, `bupr_current_batch`, `bupr_member_type_criteria` (Pro,
  read-only in free), `bupr_pro_options.review_mode` (Pro-persisted), transient `_bupr_activation_redirect`.
- All hydrated into global `$bupr` by `BUPRGlobals` (includes/class-buprglobals.php). Full map: manifest `settings`.

## Key entry points
- Main / bootstrap: `buddypress-member-review.php`
- Globals: `includes/class-buprglobals.php` (global `$bupr`, `bupr_global_settings` filter)
- Admin: `admin/bupr-admin.php` (settings, CPT, flag/report tooling)
- OLD wrapper: `admin/wbcom/wbcom-admin-settings.php`
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

## Open findings (from wppqa baseline + onboarding read)
1. **(high)** `bupr_update_review` (`includes/bupr-ajax.php:665`) verifies the front nonce but has
   NO ownership/`edit_post` check before `wp_update_post` — any logged-in user with the nonce can
   update any review by ID. (Sibling `bupr_edit_review` at :538 does check ownership.)
2. **(medium)** Cron `bupr_cron_recalculate_user_reviews_batch` scheduled with conflicting intervals:
   `daily` at activation (`buddypress-member-review.php:170`) vs `hourly` on `wp`
   (`includes/bupr-general-functions.php:365`).
3. **(medium)** Native `alert()` x3 in `assets/js/bupr-front.js:442,466,472` (admin-ux Rule 10);
   plus `Wbcom_Admin_Settings::wbcom_themes_submenu_page_callback` includes
   `templates/wbcom-themes-page.php` which is absent from the repo.

(False positive, already triaged: nonce-no-cap on `wp_allow_bupr_my_member` bupr-ajax.php:156 —
public/nopriv submission endpoint, nonce-only by design.)

## Build
`grunt` (gruntfile.js) — JS/CSS minification + RTL generation. `npm install` first.

## Recent changes
| Date | Type | Description | Files |
|------|------|-------------|-------|
| 2026-07-03 | bug-fix | Dark mode rebuilt as token pattern: dark triggers (`html[data-bx-mode="dark"]`, `auto`+`prefers-color-scheme`, legacy `body.buddyx-dark-theme`/`.dark-mode`/`.dark-scheme`) set `--bupr-*` tokens; component rules written once with light fallbacks. Covers rows, edit/flag/success modals, owner replies, report buttons, notices, pagination. | assets/css/bupr-front.css (+min/rtl) |
| 2026-06-05 | docs | Onboarding: regenerated audit/ (manifest, FEATURE_AUDIT, CODE_FLOWS, graph, wppqa baseline) + READ-FIRST CLAUDE.md | audit/, CLAUDE.md |
