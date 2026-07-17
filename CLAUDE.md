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
  (`includes/admin/class-bupr-admin-panel.php:155-162`). **PRIMARY settings page.**
- Tabs (`BUPR_Admin_Panel::get_tabs`, `:52-121`): Overview, General, Criteria, Display,
  Add-ons, Discover. The Add-ons tab keeps the legacy `bupr-pro-features` **slug** (public
  contract: `?tab=` URLs + the tab map Pro filters); only its label was reframed.

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
- ~~`wp_enqueue_style()` passes literal `false` for `$media`~~ — fixed 2026-07-17 (all six
  call sites in `admin/bupr-admin.php` + `includes/bupr-scripts.php` now pass `'all'`).
- ~~Hardcoded non-translatable `'Approving..'`~~ — fixed 2026-07-07 (localized via
  `bupr_admin_ajax_object.approving_text`).
- PHPStan has no WordPress stubs configured (baseline noise, not a code signal).
- ~~Data model (flagged 2026-07-07): duplicate reviewer→reviewee pair~~ — **investigated
  2026-07-17: working as designed, not a bug.** `bupr_multi_reviews` ("Multiple Reviews",
  default `'yes'`) gates it and IS read in two places (`includes/bupr-ajax.php:262`,
  `includes/bupr-shortcodes.php:301`). `bupr_allow_update` is unrelated — it only toggles the
  Edit button's visibility (`includes/templates/bupr-reviews-tab-template.php:69`). Remaining
  **design** gap for owner review, not ticketed as a bug: with `multi_reviews='no'` a second
  submit is **rejected** ("You already posted a review for this member.") rather than
  **updating** the existing review; no code path ever converts a submit into an update.

Open (needs owner/QA decision, raised 2026-07-17):
- ~~**Admin "Pro Features" tab contradicts the readme.**~~ — **resolved 2026-07-17**: owner chose
  "keep the mount, drop the upsell". Tab reframed to a neutral **Add-ons** surface; mount point
  preserved. See the Recent changes row below.
- **Two known cross-plugin quirks in the shared free-stack smoke env** (not this plugin):
  `buddypress-groups-review` fatals on `bp_get_current_group_id()` (500s the front end), and
  `buddypress-birthdays` hijacks unrelated page requests.

(False positive, already triaged: nonce-no-cap on `wp_allow_bupr_my_member` bupr-ajax.php:156 —
public/nopriv submission endpoint, nonce-only by design.)

## Build
`grunt` (gruntfile.js) — JS/CSS minification + RTL generation. `npm install` first.

## Recent changes
| Date | Type | Description | Files |
|------|------|-------------|-------|
| 2026-07-17 | ux | **"Pro Features" tab reframed as a neutral "Add-ons" surface** (owner decision: keep the mount, drop the upsell). Removed the Free-vs-Pro comparison table, the "Get Pro Version" CTA, the PRO badges, and the 4 disabled fake controls that did nothing. The `bupr_admin_settings_pro_content` mount point is preserved, still inside the `wbcom-tab-content` > `wbcom-admin-title-section` > `wbcom-admin-option-wrap` wrappers Pro depends on (Pro renders form contents only). Firing is now gated on `has_action()` instead of `class_exists( 'BuddyPress_Member_Review_Pro' )` + `BUPR_Pro_Features::is_pro_active()`; behaviourally identical for Pro (Pro hooks unconditionally and `is_pro_active()` is a hardcoded `return true` — licensing only gates updates via `is_license_valid()`), but it makes the tab a real add-on surface rather than Pro-coupled. When nothing is hooked, a neutral empty state renders ("No add-ons are installed."). Slug `bupr-pro-features` deliberately unchanged (public contract). Dropped the unreachable "Pro installed, not licensed" branch and ~110 lines of now-dead upsell CSS. Browser-verified 1280px + 390px, mount point proved via throwaway mu-plugin, zero PHP errors. | admin/tab-templates/bupr-setting-pro-features-tab.php, admin/tab-templates/bupr-setting-criteria-tab.php, includes/admin/views/settings-pro-features.php, includes/admin/class-bupr-admin-panel.php, assets/css/admin.css, audit/manifest.json |
| 2026-07-17 | bug-fix | **Bundled Font Awesome never worked.** `assets/css/fontawesome/all.min.css` referenced `../webfonts/` → resolved to the non-existent `assets/css/webfonts/` (fonts actually live at `assets/css/fontawesome/webfonts/`), so every bundled face 404'd and the stars only rendered because BuddyX happens to ship its own FA6. Corrected to `webfonts/`; FA enqueues now versioned `BUPR_PLUGIN_VERSION` instead of a fixed `'5.5.0'` so updated sites don't keep the broken copy cached under an unchanged URL. Verified: `document.fonts.check('900 16px "Font Awesome 5 Free"') === true`, 0 console errors. | assets/css/fontawesome/all.min.css, includes/bupr-scripts.php, admin/bupr-admin.php |
| 2026-07-17 | perf | **Dist zip 4.13 MB → ~1.32 MB (-68%).** Deleted 3 byte-identical FA4 font dirs (`admin/assets/fonts/`, `admin/assets/css/fonts/`, `admin/assets/css/min/fonts/`, 3.26 MB) + the never-enqueued `admin/assets/css/vendor/font-awesome.min.css`; all were unreachable (nothing declared `font-family: FontAwesome`). Dropped the FA4 `@font-face` from `bupr-admin.css`. Trimmed FA5 legacy `.eot/.svg/.ttf` (2.33 MB), keeping `.woff2`+`.woff`. Brands kept deliberately: unused by this plugin but our generic `font-awesome` handle may be reused by third parties. | assets/css/fontawesome/{all.min.css,webfonts/}, admin/assets/css/bupr-admin.css (+min/rtl), admin/assets/{fonts,css/fonts,css/min/fonts}/ |
| 2026-07-17 | security | Review submission derived the reviewer from the client-supplied `bupr_current_user` POST field and used it as `post_author`, the self-review guard, and the duplicate-review gate on a `nopriv` nonce-only endpoint — any visitor could forge authorship or bypass those gates. Now derived from `get_current_user_id()` with a logged-out guard (form is already login-gated; anonymous mode only swaps the display name, so it is unaffected). Dropped the write-only `$bupr_multi_reviews` variable. | includes/bupr-ajax.php |
| 2026-07-17 | bug-fix | Half-star rendered blank in 2 templates: `fa fa-star-half-o` is a FA4 class absent from the bundled FA5 (4 other call sites correctly use `fas fa-star-half-alt`). Aligned both. | includes/templates/bupr-reviews-tab-template.php, includes/templates/bupr-single-review-template.php |
| 2026-07-17 | a11y | Widget radio labels pointed `for=` at `get_field_id('Top rated')`/`('Top Viewed')`, which match no input (real IDs are `top_rated`/`top_viewed`), so clicking the labels did nothing. | includes/widgets/display-review.php |
| 2026-07-17 | refactor | Deleted 3 orphaned tab templates (`bupr-setting-shortcode-tab.php`, `bupr-setting-support-tab.php`, `bupr-welcome-page.php`) — zero references; inclusion of the 4 live siblings is literal, and the only dynamic include (`class-bupr-admin-panel.php:439`) is hardcoded to `includes/admin/views/`. Manifest `dead_code` cleared + FILE_INDEX synced. Shortcode docs (the only in-product reference) re-homed as a small card on the Display tab. | admin/tab-templates/, includes/admin/views/settings-display.php, audit/manifest.json, docs/FILE_INDEX.md |
| 2026-07-07 | ux/a11y | Resolved the 12 UX/design-system audit cards (Member Reviews Bugs column): `:root` light `--bupr-*` token block (light now themeable); merged legacy `bupr-reviews.css` into `bupr-front.css` (tokenized + logical props + focus-visible) and dequeued it (now a stub); dropped the dead `.modal`/`.close`/`#bupr_review_success_msg` system; RTL logical properties (46 physical→`*-inline-*`/`inset-inline-end`); breakpoints 480/768/1024; stripped `!important` except 3 Youzify overrides; theme-following `&times;` modal-close button (fixes hardcoded-black SVG invisible on dark); FA star widget made a keyboard-operable ARIA radiogroup; `:focus-visible`/`:disabled`/`:active` states; admin dark token scale gated behind explicit dark-admin class (not `prefers-color-scheme` — WP admin core is light); localized `'Approving…'`. Browser-verified on BuddyX 5.1.3 (light/dark/RTL/390px, keyboard rating + submit, admin). | assets/css/bupr-front.css (+min/rtl), assets/css/admin.css (+min/rtl), assets/css/bupr-reviews.css (stub), includes/bupr-scripts.php, includes/templates/bupr-edit-review-form.php, assets/js/bupr-front.js (+min), admin/assets/js/bupr-admin.js (+min) |
| 2026-07-03 | bug-fix | Approve Review admin AJAX success check now branches on the stable `response.data.code` (`review-approved-successfully`) instead of the translated message — approve flow no longer hangs on non-English installs | admin/assets/js/bupr-admin.js (+min) |
| 2026-07-03 | a11y | `:focus-visible` outline restored for `.bupr-reply-textarea`; label[for]/aria-label added to unlabeled settings inputs (email subject/message fields, Multiple Criteria switch, dynamic criteria rows + JS row generator) | assets/css/bupr-front.css (+min/rtl), admin/tab-templates/bupr-setting-{general,criteria}-tab.php, admin/assets/js/bupr-admin.js |
| 2026-07-03 | refactor | Deleted orphaned `bupr-member-types-criteria-tab.php` (dead template with unregistered options.php form); manifest/FILE_INDEX synced | admin/tab-templates/, audit/manifest.json, docs/FILE_INDEX.md |
| 2026-07-03 | perf | `[bupr_display_top_members]` CAST-ordered usermeta query cached in 15-min versioned transient; `bupr_flush_top_members_cache()` bumps the salt on recalc/status-transition/delete | includes/bupr-shortcodes.php, includes/bupr-general-functions.php |
| 2026-07-03 | docs | Version sync (package.json + .pot → 3.8.0); CLAUDE.md wrapper classification updated to NEW card-panel shell; open findings refreshed | package.json, languages/bp-member-reviews.pot, CLAUDE.md |
| 2026-07-03 | bug-fix | Dark mode rebuilt as token pattern: dark triggers (`html[data-bx-mode="dark"]`, `auto`+`prefers-color-scheme`, legacy `body.buddyx-dark-theme`/`.dark-mode`/`.dark-scheme`) set `--bupr-*` tokens; component rules written once with light fallbacks. Covers rows, edit/flag/success modals, owner replies, report buttons, notices, pagination. | assets/css/bupr-front.css (+min/rtl) |
| 2026-06-05 | docs | Onboarding: regenerated audit/ (manifest, FEATURE_AUDIT, CODE_FLOWS, graph, wppqa baseline) + READ-FIRST CLAUDE.md | audit/, CLAUDE.md |
