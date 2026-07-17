# BUILD-LOG — Member Reviews wrapper migration (3.7.1 → 3.8.0)

Plugin: **buddypress-member-review** ("Wbcom Designs - BuddyPress Member Reviews")
Base branch: `3.7.1` → shipped as **3.8.0**
Migration: OLD Wbcom tab wrapper (`admin/wbcom/`) → new card-panel admin.
Date: 2026-06-05

---

## Summary

The legacy `admin/wbcom/` shared-chrome wrapper + horizontal `nav-tab`
settings page was replaced with the card-panel admin pattern (shell +
sidebar nav + card body), mirroring the shipped `buddypress-contact-me`
reference. All three option groups, every `bupr_*` hook, the review CPT /
taxonomy / meta box / flag tooling, and the Pro coupling surface are
preserved. Two approved safe fixes (AJAX ownership check, cron
de-duplication) and the front-end alert replacement were applied.

No commits, no push, no activation, no DB writes were performed.

---

## Files DELETED (1 dir, ~10 files)

- `admin/wbcom/` (entire directory):
  - `wbcom-admin-settings.php` (class `Wbcom_Admin_Settings` — top header nav,
    Our Plugins/Themes/Support submenus, plugin-installer AJAX, the
    `[wbcom_admin_setting_header]` shortcode, the `wbcom_addons_cards` AJAX).
  - `templates/wbcom-plugins-page.php`, `templates/wbcom-support-page.php`,
    `templates/wbcom-license-page.php`.
  - `assets/{css,css/min,css/rtl,js,js/min}/wbcom-admin-setting*`.
  - `assets/imgs/bp_member_review.png`.

The dead `templates/wbcom-themes-page.php` include target (manifest finding
`missing_include_targets`) is resolved by deleting its caller with the dir.

## Files CREATED (10)

- `includes/admin/class-bupr-admin-panel.php` — controller (class
  `BUPR_Admin_Panel`): `get_tabs()`, `register()`, `add_menu()`,
  `takeover_hub_landing()`, `register_settings()` + 3 sanitizers,
  `enqueue_assets()`, `is_our_screen()`, `suppress_foreign_notices()`,
  `render_hub()`, `render_page()`.
- `includes/admin/views/shell.php` — header card + sidebar nav + body slot
  (+ `wp-header-end` marker; tab partials own their own forms).
- `includes/admin/views/hub.php` — WB Plugins hub landing (wrapper-helper
  slugs filtered out via `wbcom_hub_wrapper_helper_slugs`).
- `includes/admin/views/overview.php` — dashboard (review stats from CPT +
  usermeta, reported-reviews warning, config snapshot, quick actions).
- `includes/admin/views/settings-general.php`
- `includes/admin/views/settings-criteria.php`
- `includes/admin/views/settings-display.php`
- `includes/admin/views/settings-pro-features.php`
  (the four settings partials each wrap the EXISTING
  `admin/tab-templates/bupr-setting-*-tab.php` 1:1 inside a card, so the
  field markup, hidden defaults, and Save buttons are byte-identical.)
- `assets/css/admin.css` — `--bupr-admin-*` token system, card panel,
  sidebar, hub grid, toast/confirm, legacy `.wbcom-*` content harmonization,
  Free-vs-Pro comparison-table styles (relocated from inline `<style>`),
  2 media blocks.
- `assets/js/admin.js` — `window.buprToast()` + `window.buprConfirm()` +
  `[data-bupr-confirm]` interceptor (no native dialogs).

## Files MODIFIED (8)

- `buddypress-member-review.php` — version 3.7.1 → 3.8.0 (header +
  `BUPR_PLUGIN_VERSION`); dropped `admin/wbcom/wbcom-admin-settings.php`
  from the include list, added `includes/admin/class-bupr-admin-panel.php`
  and booted `BUPR_Admin_Panel` under `is_admin()`; removed the
  activation-time cron scheduling (now single-sourced — see cron fix).
- `admin/bupr-admin.php` — removed the legacy UI surface
  (`bupr_add_submenu_page_admin_settings`, `bupr_plugin_settings`,
  `bupr_admin_options_page`, `bupr_plugin_settings_tabs`, the five
  `bupr_admin_*_content` includers, `wbcom_hide_all_admin_notices_from_setting_page`,
  the unused `$plugin_settings_tabs` property) and dropped their hooks from
  the constructor. KEPT all non-UI service logic: review CPT + taxonomy,
  rating meta box, flag/report columns + filters + bulk + admin-post
  handlers, pending-count menu badge (`bupr_get_review_count`), the
  review→activity/email transition, the criteria `pre_update` transform
  (`bupr_update_admin_settings`), and the shared `bupr_sanitize_options()`
  the new panel delegates to.
- `includes/bupr-general-functions.php` — cron de-dup safe fix (below).
- `includes/bupr-ajax.php` — AJAX ownership safe fix (below).
- `assets/js/bupr-front.js` — replaced 3 native `alert()` with a
  self-contained `window.buprFrontToast()` (added at top of file).
- `assets/css/bupr-front.css` — appended front-toast styles.
- `admin/class-bupr-admin-feedback.php` — the feedback-notice icon pointed
  at the deleted `admin/wbcom/assets/imgs/bp_member_review.png`; swapped to a
  `dashicons-testimonial` so no asset 404s.
- `admin/tab-templates/bupr-setting-pro-features-tab.php` — removed the
  inline `<style>` block (relocated to `assets/css/admin.css`, skill gate F1).
- `gruntfile.js` — removed the three `admin/wbcom/assets` build targets
  (`cssmin:wbcom`, `uglify:wbcom`, the third `rtlcss` file entry).
- `readme.txt` + `README.txt` — `Stable tag: 3.7.1` → `3.8.0`, 3.8.0
  changelog entry prepended to both.

---

## Option keys preserved (old → new proof)

All three `register_setting()` groups moved from `BUPR_Admin::bupr_plugin_settings()`
to `BUPR_Admin_Panel::register_settings()` with identical group + option names:

| Option group / name (old) | Option group / name (new) | Sanitizer |
|---|---|---|
| `bupr_admin_general_options` | `bupr_admin_general_options` | legacy `bupr_sanitize_options()` (via `sanitize_general`) |
| `bupr_admin_settings` | `bupr_admin_settings` | legacy `bupr_sanitize_options()` (via `sanitize_criteria`) + `pre_update_option_bupr_admin_settings` filter (unchanged) |
| `bupr_admin_display_options` | `bupr_admin_display_options` | legacy `bupr_sanitize_options()` (via `sanitize_display`) |

Save semantics preserved: each group lives on exactly ONE tab (old and new),
so the sanitizers do a full per-group rebuild from input — identical to the
pre-migration form (unchecked toggles without a hidden companion, e.g.
`bupr_hide_review_button`, `bupr_enable_anonymous_reviews`,
`bupr_enable_review_activity`, still persist as "off"/default). The single
sentinel carve-out (Playbook 7.1): `sanitize_general()` restores
`review_approve_email_subject` / `review_approve_email_message` from the
stored option when they were hidden (auto-approve ON) so they are not wiped.

All 20 general subkeys, the criteria subkeys, and the display subkeys
(`bupr_review_title`, `bupr_review_title_plural`, `bupr_star_color`) are
preserved. Standalone options (`bupr_plugin_version`, `bupr_current_batch`,
`bupr_member_type_criteria`, `bupr_pro_options`) untouched.

## Pro coupling preserved

- Every `bupr_*` hook is intact (no renames). The Pro addon's
  `bupr_admin_settings_tabs` filter (used to remove the free Criteria tab)
  is still applied inside `BUPR_Admin_Panel::get_tabs()` via a legacy-shaped
  `tab_key => label` map, so Pro tab removal continues to work without a Pro
  release. New rich filter `bupr_admin_panel_tabs` added alongside.
- `bupr_admin_settings_criteria_content` / `bupr_admin_settings_pro_content`
  actions still fire from the ported criteria / pro-features tab templates.
- `bupr_global_settings`, `bupr_aggregate_rating`, `bupr_before_save_review`,
  etc. unchanged.
- No EDD license tab exists in this plugin (no `edd-license/`, no
  `vendor/edd-sl-sdk`) → Playbook Part 8 intentionally skipped.

## Safe fixes applied

1. **SECURITY (manifest finding #1, high) — `bupr_update_review` ownership.**
   `includes/bupr-ajax.php::bupr_update_review()` verified only the front
   `review-nonce` before `wp_update_post()`, letting any logged-in user edit
   any review by ID. Added the SAME ownership gate the sibling
   `bupr_edit_review()` uses (`get_current_user_id() === post_author OR
   current_user_can('edit_others_posts')`), plus a post-type validation,
   BEFORE the update. Returns a `permission_error` JSON otherwise.

2. **Cron de-duplication (manifest finding #2, medium).** The
   `bupr_cron_recalculate_user_reviews_batch` hook was scheduled twice with
   conflicting intervals — `daily` at activation
   (`buddypress-member-review.php`) and `hourly` on the `wp` hook
   (`bupr-general-functions.php`). Now scheduled in exactly one place:
   a guarded `init` registration at the `daily` interval. The activation
   scheduling was removed; the `wp`-hook hourly registration was changed to
   the single `init`/`daily` source.

3. **Native `alert()` removal (manifest `alert_usage`).** The 3 native
   `alert()` calls in `assets/js/bupr-front.js` (owner-reply empty, reply
   failure, generic error) now call `window.buprFrontToast(msg, 'error')`.

4. **Version bump.** 3.7.1 → 3.8.0 across plugin header, `BUPR_PLUGIN_VERSION`,
   and both readmes; changelog stub added.

5. **Inline `<style>` removal (skill gate F1).** Moved the Free-vs-Pro
   comparison-table CSS out of `bupr-setting-pro-features-tab.php` into
   `assets/css/admin.css`.

## Verification

- `php -l` — clean on all 14 created/modified PHP files (0 syntax errors).
- WPCS (`wpcs` MCP) — **0 errors** on all changed files. Remaining warnings
  are all PRE-EXISTING in untouched code (`error_log`, `meta_query`,
  `current_time('timestamp')`, direct-DB-no-caching) — none introduced by
  this migration. The controller, shell, hub, overview, and all settings
  partials report "No coding standard violations found."
- `node -c` — `assets/js/admin.js` + `assets/js/bupr-front.js` parse clean.
- Dangling-reference greps (Playbook Part 2.1) — no PHP/JS/shortcode
  references to `admin/wbcom`, `wbcom-admin-settings`,
  `wbcom_admin_setting_header`, `wbcom_addons_cards`, or
  `wbcom_plugin_installer_params` remain (only two explanatory code
  comments).
- Option-name grep (old vs new) — the three group names match exactly.

## TODOs / needs human eyes

1. **Rebuild minified front assets.** `assets/js/min/bupr-front.min.js` and
   `assets/css/min/bupr-front.min.css` are STALE — the `node_modules`
   toolchain is not installed here, so `grunt` / `npm run build` could not be
   run. The old `alert()` still lives in the minified JS, which the site
   serves in production (non-`SCRIPT_DEBUG`). **The release pipeline must run
   `npm install && grunt` so the alert fix + front-toast CSS land in the
   shipped min files.** (Source files are correct.)
2. **Live browser smoke is pending activation.** The plugin is inactive on
   this site and the brief forbids activation, so the card-panel render,
   each tab's save round-trip, the General-tab toggles/select2, the criteria
   builder, and the reported-reviews warning were verified statically only.
   Run the Playbook Part 13 browser checklist before release.
3. **README header vs plugin-file header.** `readme.txt`/`README.txt` declare
   `Requires at least: 5.0` / `Requires PHP: 7.4`, but the main plugin file
   header declares neither. Plugin Check flags this mismatch — pre-existing;
   consider adding `Requires at least:` / `Requires PHP:` to the main file
   header in a dedicated PCP pass (out of scope for the UX migration).
4. **`.pot` regeneration.** New user-facing strings were added (overview,
   shell, hub, panel i18n). Regenerate `languages/bp-member-reviews.pot` at
   release time (`grunt`'s `shell:wpcli` task / `wp i18n make-pot`).
