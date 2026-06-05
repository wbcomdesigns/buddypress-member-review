# Wrapper Audit — BuddyPress Member Reviews

Plugin: `buddypress-member-review`
Branch: `3.7.1` (code actually shipped as **3.8.0** — header version bumped in this migration)
Wrapper type: **NEW card-panel admin** (`includes/admin/views/shell.php` present)
Audit date: 2026-06-05
Auditor: AutoVAP (read-only)

---

## 1. Controller Class Correctness

Controller: `includes/admin/class-bupr-admin-panel.php` — class `BUPR_Admin_Panel`

| Method | Present? | Notes |
|---|---|---|
| `get_tabs()` | YES | Static; returns 5 tabs + passes through legacy `bupr_admin_settings_tabs` filter for Pro back-compat |
| `register()` | YES | Hooks `admin_menu`, `admin_init`, `admin_enqueue_scripts`, `in_admin_header` |
| `add_menu()` | YES | Creates `wbcomplugins` hub (if absent) + `bp-member-review-settings` submenu, `manage_options` cap |
| `takeover_hub_landing()` | YES | Pri-999 `admin_menu`, replaces hub callback with `render_hub()` |
| `register_settings()` | YES | All three groups: `bupr_admin_general_options`, `bupr_admin_settings`, `bupr_admin_display_options` |
| `sanitize_general()` | YES | Calls `run_legacy_sanitizer()` + sentinel-guards approval-email keys |
| `sanitize_criteria()` | YES | Delegates to `run_legacy_sanitizer()` |
| `sanitize_display()` | YES | Delegates to `run_legacy_sanitizer()` |
| `enqueue_assets()` | YES | Screen-scoped via `is_our_screen()` |
| `is_our_screen()` | YES | Matches `_page_bp-member-review-settings` + `toplevel_page_wbcomplugins` |
| `suppress_foreign_notices()` | YES | Hooked to `in_admin_header` (not `all_admin_notices`/`admin_notices`); removes both |
| `render_hub()` | YES | Includes `includes/admin/views/hub.php` via `BUPR_PLUGIN_PATH` |
| `render_page()` | YES | Routes active tab → view file via `BUPR_PLUGIN_PATH`; includes shell |

All contract methods present. The brief asks for `admin_notices`/`all_admin_notices` in `register()` — the class instead hooks `in_admin_header` and removes both notice actions from inside that callback, which achieves the same result. Contract satisfied.

**Path-constant check: PASS**

- `BUPR_PLUGIN_PATH` is defined at `buddypress-member-review.php:24` as `plugin_dir_path( __FILE__ )` where `__FILE__` is the plugin root file. Resolves to `.../buddypress-member-review/`.
- `BUPR_PLUGIN_URL` is defined at `buddypress-member-review.php:25` as `plugin_dir_url( __FILE__ )` from the same root file.
- All view includes in the controller use `BUPR_PLUGIN_PATH . 'includes/admin/views/…'` — correct root-relative.
- Asset enqueues use `BUPR_PLUGIN_URL . 'assets/css/admin.css'` and `BUPR_PLUGIN_URL . 'assets/js/admin.js'` — both files exist at plugin root. No 404 risk.
- The prior bug reported in the audit brief (a constant defined inside `includes/` that produced blank body + 404 assets) is **not present** here. Both constants are defined in the root file.

---

## 2. Wrapper Structure / Gaps

| Item | Status |
|---|---|
| `includes/admin/views/shell.php` | EXISTS — header card, version pill, sidebar nav from `get_tabs()`, `<main>` body slot that includes `$view_path`, `wp-header-end` marker |
| `includes/admin/views/hub.php` | EXISTS — renders WB Plugins hub; filters out legacy helper slugs via `wbcom_hub_wrapper_helper_slugs` filter |
| All tabs in `get_tabs()` have matching view files | YES — see Tab↔View map below |
| No dead nav links | PASS |
| No orphan views | PASS |
| Legacy `admin/wbcom/` directory | DELETED — confirmed absent from repo |
| Legacy `includes/shared-admin/` directory | Never existed in this plugin — N/A |
| `[wbcom_admin_setting_header]` shortcode | GONE — caller file deleted |
| Dangling refs to old wrapper handles/paths | NONE found in PHP (comments referencing the deleted dir are documentation-only in `class-bupr-admin-panel.php` and `buddypress-member-review.php`) |

### Tab↔View Map

| Tab slug | View file | Exists? | Option group |
|---|---|---|---|
| `overview` | `includes/admin/views/overview.php` | YES | (none — read-only dashboard) |
| `bupr-general` | `includes/admin/views/settings-general.php` | YES | `bupr_admin_general_options` |
| `bupr-criteria` | `includes/admin/views/settings-criteria.php` | YES | `bupr_admin_settings` |
| `bupr-display` | `includes/admin/views/settings-display.php` | YES | `bupr_admin_display_options` |
| `bupr-pro-features` | `includes/admin/views/settings-pro-features.php` | YES | (none — read-only or Pro-owned) |

No dead links. No orphan views. The `settings-*.php` partials each `require` the matching `admin/tab-templates/bupr-setting-*-tab.php` using `BUPR_PLUGIN_PATH` (root-constant). Those templates exist.

---

## 3. Option Wiring — End-to-End

### Group A: `bupr_admin_general_options` (20 subkeys)

Registered: `BUPR_Admin_Panel::register_settings()` with sanitize callback `sanitize_general()`.
Rendered: `admin/tab-templates/bupr-setting-general-tab.php` (wrapped by `settings-general.php`).
Saved: `sanitize_general()` → `run_legacy_sanitizer()` → `BUPR_Admin::bupr_sanitize_options()`.
Read back: `BUPRGlobals::bupr_review_globals()` hydrates global `$bupr` on `init` from `get_option('bupr_admin_general_options')`.

Subkey-by-subkey verdict:

| Subkey | Field name in form | Registered | Rendered | Saved+Sanitized | Read back | Verdict |
|---|---|---|---|---|---|---|
| `bupr_auto_approve_reviews` | `bupr_admin_general_options[bupr_auto_approve_reviews]` | YES | YES (checkbox + hidden default) | YES | YES (`auto_approve_reviews` in `$bupr`) | PASS |
| `profile_reviews_per_page` | `bupr_admin_general_options[profile_reviews_per_page]` | YES | YES (number input) | YES | YES (`reviews_per_page`) | PASS |
| `bupr_member_dir_reviews` | `bupr_admin_general_options[bupr_member_dir_reviews]` | YES | YES (checkbox) | YES | YES (`dir_view_ratings`) | PASS |
| `bupr_member_dir_add_reviews` | NOT rendered in free tab | YES (reg) | NO (not in general tab) | N/A | YES (read with default 'yes') | MINOR — field registered, not rendered. Field is read in `$bupr` with a default. Not a data-loss risk; admin just cannot configure it from free UI. (Likely a Pro-only field.) |
| `bupr_allow_email` | `bupr_admin_general_options[bupr_allow_email]` | YES | YES | YES | YES (`allow_email`) | PASS |
| `bupr_allow_notification` | `bupr_admin_general_options[bupr_allow_notification]` | YES | YES | YES | YES (`allow_notification`) | PASS |
| `bupr_allow_update` | `bupr_admin_general_options[bupr_allow_update]` | YES | YES | YES | YES (`allow_update`) | PASS |
| `bupr_exc_member[]` | `bupr_admin_general_options[bupr_exc_member][]` | YES | YES (multi-select) | YES | YES (`exclude_given_members`) | PASS |
| `bupr_add_member[]` | `bupr_admin_general_options[bupr_add_member][]` | YES | YES (multi-select) | YES | YES (`add_taken_members`) | PASS |
| `bupr_multi_reviews` | `bupr_admin_general_options[bupr_multi_reviews]` | YES | YES (checkbox + hidden default) | YES | YES (`multi_reviews`) | PASS |
| `bupr_hide_review_button` | `bupr_admin_general_options[bupr_hide_review_button]` | YES | YES (checkbox) | YES | YES (`hide_review_button`) | PASS |
| `bupr_enable_anonymous_reviews` | `bupr_admin_general_options[bupr_enable_anonymous_reviews]` | YES | YES (checkbox) | YES | YES (`anonymous_reviews`) | PASS |
| `bupr_enable_review_activity` | `bupr_admin_general_options[bupr_enable_review_activity]` | YES | YES (checkbox) | YES | YES (`review_activity`) | PASS |
| `review_email_subject` | `bupr_admin_general_options[review_email_subject]` | YES | YES (text input) | YES | YES (read in ajax + options) | PASS |
| `review_email_message` | `bupr_admin_general_options[review_email_message]` | YES | YES (wp_editor) | YES | YES | PASS |
| `review_approve_email_subject` | `bupr_admin_general_options[review_approve_email_subject]` | YES | YES (conditionally, when auto-approve is OFF) | YES (sentinel-guarded in `sanitize_general`) | YES | PASS |
| `review_approve_email_message` | `bupr_admin_general_options[review_approve_email_message]` | YES | YES (conditionally) | YES (sentinel-guarded) | YES | PASS |
| `bupr_enable_owner_replies` | `bupr_admin_general_options[bupr_enable_owner_replies]` | YES | YES (checkbox + hidden) | YES | YES (`enable_owner_replies`) | PASS |
| `bupr_enable_flagging` | `bupr_admin_general_options[bupr_enable_flagging]` | YES | YES (checkbox + hidden) | YES | YES (`enable_flagging`) | PASS |
| `bupr_flag_threshold` | `bupr_admin_general_options[bupr_flag_threshold]` | YES | YES (number input) | YES | YES (`flag_threshold`) | PASS |

**19/20 PASS. 1 MINOR** (`bupr_member_dir_add_reviews` — registered and read-back with default, not rendered in free UI; appears intentionally Pro-gated).

### Group B: `bupr_admin_settings` (criteria)

Registered: `register_settings()` with `sanitize_criteria()`.
Rendered: `admin/tab-templates/bupr-setting-criteria-tab.php` — form submits to `options.php` with `settings_fields('bupr_admin_settings')`.
Saved: `sanitize_criteria()` → `run_legacy_sanitizer()`. Additionally the `pre_update_option_bupr_admin_settings` filter in `BUPR_Admin::bupr_update_admin_settings()` converts `rating_field_name[]` raw input rows into `profile_rating_fields` AFTER the sanitizer — this transform is preserved.
Read back: `BUPRGlobals` reads `profile_multi_rating_allowed` and `profile_rating_fields` from this option.

| Subkey | Verdict |
|---|---|
| `profile_multi_rating_allowed` | PASS — checkbox + hidden default in form; maps to `multi_criteria_allowed` in `$bupr` |
| `profile_rating_fields` | PASS — built by the `pre_update_option` transform from `rating_field_name[]` inputs; read back as `active_rating_fields` in `$bupr` |

### Group C: `bupr_admin_display_options`

Registered: `register_settings()` with `sanitize_display()`.
Rendered: `admin/tab-templates/bupr-setting-display-tab.php`.
Saved: `sanitize_display()` → `run_legacy_sanitizer()`.
Read back: `BUPRGlobals` reads `bupr_review_title`, `bupr_review_title_plural`, `bupr_star_color`.

| Subkey | Verdict |
|---|---|
| `bupr_review_title` | PASS |
| `bupr_review_title_plural` | PASS |
| `bupr_star_color` | PASS |

---

## 4. Multi-Tab Data-Loss Guard

**Status: PRESENT and correctly scoped.**

All three option groups map 1:1 to one tab each — General tab → `bupr_admin_general_options`, Criteria tab → `bupr_admin_settings`, Display tab → `bupr_admin_display_options`. Saving one tab cannot affect another group; WordPress's `register_setting()` per-group isolation handles this.

The ONE intra-group carve-out is in `sanitize_general()`: the conditional-render approval-email fields (`review_approve_email_subject` / `review_approve_email_message`) are only present in POST when auto-approve is OFF. When auto-approve is ON those keys are absent, so the sentinel guard reads them from `get_option` and re-merges before saving. This matches Playbook 7.1 exactly. Implementation is correct.

---

## 5. Security Fix — `bupr_update_review` Ownership Check

**Status: FIXED. Confirmed correct.**

`includes/bupr-ajax.php:720-728` now contains:

```php
if ( get_current_user_id() !== (int) $review->post_author && ! current_user_can( 'edit_others_posts' ) ) {
    wp_send_json_error(
        array(
            'message' => __( 'You do not have permission to edit this review.', 'bp-member-reviews' ),
            'code'    => 'permission_error',
        )
    );
    return;
}
```

This is inserted between the post-existence check (line 700) and the content read (line 730), before `wp_update_post()` is called at line 744. The check mirrors `bupr_edit_review()` exactly (same condition at line 538). The fix is present, correctly positioned, and logically equivalent to the sibling handler.

The CLAUDE.md open finding #1 and the manifest `static_analysis.missing_handlers` entry for this handler are now resolved.

---

## 6. Cron De-duplication

**Status: FIXED. Deterministic single registration.**

`buddypress-member-review.php` activation hook (line 168–173) now contains only a comment explaining that the cron is NOT scheduled at activation — the conflicting `daily` schedule that used to live there has been removed.

`includes/bupr-general-functions.php:369–374` contains the single authoritative registration:

```php
function bupr_schedule_review_recalculation() {
    if ( ! wp_next_scheduled( 'bupr_cron_recalculate_user_reviews_batch' ) ) {
        wp_schedule_event( time(), 'daily', 'bupr_cron_recalculate_user_reviews_batch' );
    }
}
add_action( 'init', 'bupr_schedule_review_recalculation' );
```

The prior conflicting `hourly` registration on the `wp` hook is gone. One hook, one interval (`daily`), guarded by `wp_next_scheduled()`. The CLAUDE.md open finding #2 is resolved.

The manifest `cron[0].note` ("Scheduled with conflicting intervals in two places") is stale — the manifest was generated at 3.7.1 and the fix landed in 3.8.0. The manifest needs a refresh.

---

## 7. Native alert() Replacement

**Status: FIXED. All three calls replaced.**

The audit brief and manifest recorded three `alert()` calls at `assets/js/bupr-front.js:442,466,472`. Grep over that file finds zero `alert(` calls that are not inside comments. The file now begins with `window.buprFrontToast()` — a self-contained toast helper with ARIA `role="status"` and `aria-live="polite"`. All three previously-alert sites now call `window.buprFrontToast(message, tone)`.

The panel-side JS (`assets/js/admin.js`) exposes `window.buprToast()` and `window.buprConfirm()` (a Promise-based modal with keyboard handling). Neither admin nor front JS uses `alert()` or `confirm()`.

---

## 8. Hygiene Checks

### alert()/confirm()
PASS — see section 7 above.

### CSS Tokens
PASS. `assets/css/admin.css` defines a complete `--bupr-admin-*` token set scoped to `.bupr-admin`. All layout, color, spacing, shadow, and radius values in the panel CSS use those tokens. No raw hex or px values appear in properties that should be token-driven.

NOTE: The `.bupr-snav-link` uses `padding: 9px 10px` (approximately 36px effective height including 13px font at 1.4 line-height = ~18px + 18px padding = 36px). This is 4px below the 40px tap-target rule. `.bupr-btn` correctly declares `min-height: 40px`. The sidebar nav links are the one gap.

### Screen-scoped enqueue
PASS. `enqueue_assets()` calls `is_our_screen()` and returns early if not on the plugin's screen or the hub. No panel assets load on unrelated admin pages.

### Responsive
The two-column `bupr-settings-layout` (sidebar + main) layout uses CSS Grid. Spot-check of the CSS shows a `@media` breakpoint at 782px that stacks the layout. RTL is handled via separate `.rtl.css` files for frontend assets (already existing). The admin panel CSS does not show explicit `margin-left`/`margin-right` raw directional properties — no issue identified.

### Focus rings
`bupr-snav-link:focus-visible` has `outline: 2px solid var(--bupr-admin-accent)`. The suppressed `outline: none` on `:focus:not(:focus-visible)` is the correct modern pattern. PASS.

---

## 9. Pro Pair Hook Integrity

All hooks consumed by the Pro addon are confirmed intact in the 3.8.0 code:

| Hook | Location | Status |
|---|---|---|
| `bupr_admin_settings_tabs` filter | `class-bupr-admin-panel.php:93` | PRESENT — passed through `get_tabs()` back-compat shim |
| `bupr_admin_panel_tabs` filter | `class-bupr-admin-panel.php:109` | PRESENT (new, for rich descriptor extension) |
| `bupr_global_settings` filter | `class-buprglobals.php:131` | PRESENT |
| `bupr_before_save_review` action | `bupr-ajax.php:321` | PRESENT |
| `bupr_admin_settings_criteria_content` action | `bupr-setting-criteria-tab.php:63` | PRESENT |
| `bupr_admin_settings_pro_content` action | `bupr-setting-pro-features-tab.php` (in template) | PRESENT |
| `bupr_edit_review_extra_fields` filter | `bupr-ajax.php:638` | PRESENT |
| `bupr_after_rating_fields` action | `bupr-shortcodes.php:463` | PRESENT |
| `bupr_shortcode_show_stars` filter | `bupr-shortcodes.php:127` | PRESENT |
| `bupr_aggregate_rating` filter | `bupr-general-functions.php:305` | PRESENT |
| `bupr_after_review_content` / `bupr_after_review_date` | templates | PRESENT |

No Pro coupling surface was broken by the migration.

---

## 10. Severity-Ranked Findings

| # | Severity | Area | Finding | File:line |
|---|---|---|---|---|
| 1 | LOW | Option wiring | `bupr_member_dir_add_reviews` is registered and read-back with default `'yes'` in `BUPRGlobals` but has no rendered field in the free General tab. Admins cannot configure it from the free UI. Appears intentionally Pro-gated but is not documented as such. | `class-buprglobals.php:66` / General tab template |
| 2 | LOW | Tap targets | `.bupr-snav-link` effective height is ~36px (9px top + 9px bottom padding + 13px/1.4 text = ~36px), 4px under the 40px rule. `.bupr-btn` already has `min-height:40px`. | `assets/css/admin.css:208` |
| 3 | LOW | Documentation drift | `audit/manifest.json` AJAX entry for `bupr_update_review` still says `"capability": "NONE - no ownership/cap check"` and the `cron[0].note` still says "conflicting intervals". Both findings were fixed in 3.8.0. The manifest was generated at 3.7.1 and was not refreshed after migration. | `audit/manifest.json:28,142` |
| 4 | INFO | Wrapper helper slugs | `hub.php` filter-list for `wbcom_hub_wrapper_helper_slugs` references `wbcom-plugins-page`, `wbcom-themes-page`, `wbcom-support-page`, `wbcom-license-page` — these slugs are no longer registered (their caller was deleted). The filter list is harmlessly over-defensive; no functional issue. | `includes/admin/views/hub.php:24-27` |

**No blocker or high-severity findings.**

---

## Overall Verdict

**SHIP-CLEAN**

All items from the specific checks in the audit brief are satisfied:

- **Security fix (`bupr_update_review` ownership check):** CONFIRMED PRESENT AND CORRECT at `bupr-ajax.php:720`.
- **Cron de-duplication:** CONFIRMED — single `daily` registration on `init`, activation hook no longer schedules. 
- **alert() replacement:** CONFIRMED — all three replaced with `buprFrontToast()`.
- **Path-constant:** CONFIRMED PASS — both constants defined in the plugin root file.
- **Option wiring (all three groups):** CONFIRMED end-to-end. 19/20 General subkeys fully wired; 1 minor gap (`bupr_member_dir_add_reviews` not rendered, appears intentionally Pro-gated).
- **Multi-tab data-loss guard:** CONFIRMED — per-group isolation + sentinel guard on conditional-render keys.
- **Legacy wrapper fully removed:** CONFIRMED — `admin/wbcom/` directory absent, no dangling PHP references to the old class.
- **Pro hook surface:** CONFIRMED intact — all `bupr_*` hooks consumed by the Pro addon are present.
- **Controller contract:** All methods present and correctly implemented.
- **Tab↔View map:** Complete, no dead links, no orphan views.

The three remaining items (one minor option gap, one CSS tap-target shortfall on sidebar nav links, one stale manifest entry) do not block shipping. They are polish-level items for the next pass.
