# Feature Audit — BuddyPress Member Reviews 3.7.1

Source of truth: `audit/manifest.json`. Branch `3.7.1`. Free Wbcom BuddyPress addon
(`buddypress-member-review`), paired with a separate Pro addon
(`buddypress-member-review-pro`) consumed exclusively via `bupr_*` hooks/filters.

## 1. Frontend tabs / pages (BuddyPress profile)
Registered in `includes/bupr-filters.php::BUPR_Custom_Hooks` via `bp_core_new_nav_item` /
`bp_core_new_subnav_item` on `bp_setup_nav` (BuddyPress) or `wp` (BuddyBoss).

| Tab / subnav | Slug | Screen function | Roles | Gating |
|---|---|---|---|---|
| Reviews (parent) | `{review_label_plural}` (default `reviews`) | `bupr_reviews_tab_function_to_show_screen` | displayed user | `bupr_show_profile_review_tab` filter |
| View | `view` (`bupr_default_subnav_slug`) | `bupr_view_review_tab_function_to_show_screen` | all | — |
| Add review | `add-{review_label}` | `bupr_reviews_form_tab_function_to_show_screen` | logged-in, role in `bupr_exc_member` | reviewer/reviewee role maps |

Templates: `includes/templates/bupr-reviews-tab-template.php`, `bupr-single-review-template.php`,
`bupr-edit-review-form.php` (theme-overridable under `your-theme/buddypress-member-review/`).

## 2. AJAX handlers (7) — `includes/bupr-ajax.php` (+ shared admin)
| Action | nopriv | Nonce | Capability | Purpose |
|---|---|---|---|---|
| `allow_bupr_member_review_update` | yes | review-nonce | none (public) | Submit new review |
| `bupr_approve_review` | yes | bupr_member_review_ajax | edit_posts | Approve draft -> publish |
| `bupr_edit_review` | no | review-nonce | author OR edit_others_posts | Return edit form HTML |
| `bupr_update_review` | no | review-nonce | **NONE (FINDING #1)** | Save edited review |
| `bupr_add_owner_reply` | no | review-nonce | profile owner OR manage_options | Owner reply |
| `bupr_flag_review` | no | review-nonce | logged-in (by design) | Report a review |
| `wbcom_addons_cards` | no | wbcom_admin_setting_nonce | none (echo only) | Shared-admin helper |

admin-post (admin/bupr-admin.php): `bupr_dismiss_flag`, `bupr_resolve_flag` — per-post
nonce + `edit_post`. Both safe.

## 3. REST endpoints
_None in free._ (REST API is a Pro feature, `/wp-json/bupr/v1/` in the Pro addon.)

## 4. Admin pages & settings
Menu: top-level `wbcomplugins` (shared "WB Plugins", registered only if not already present)
-> submenu `bp-member-review-settings` ("Member Reviews") = PRIMARY settings page.
Wrapper-added submenus: `wbcom-plugins-page`, `wbcom-themes-page`, `wbcom-support-page`.
Hook: `admin_menu`. Settings page uses the OLD tab UI (`bupr_plugin_settings_tabs()`),
tabs filterable via `bupr_admin_settings_tabs`.

Registered settings (`register_setting` in `admin/bupr-admin.php`):
- `bupr_admin_general_options` (20 subkeys — approval, per-page, dir display, email/notif,
  role gating, multi/anonymous/activity, owner-replies, flagging + threshold)
- `bupr_admin_settings` (criteria: `profile_multi_rating_allowed`, `profile_rating_fields`)
- `bupr_admin_display_options` (`bupr_review_title`, `_plural`, `bupr_star_color`)
Other options: `bupr_plugin_version`, `bupr_current_batch` (cron cursor),
`bupr_member_type_criteria` (Pro-populated, read-only in free), `bupr_pro_options.review_mode`
(field rendered in free preview tab; persisted by Pro). Full read/write map in manifest `settings`.

## 5. Shortcodes (2)
- `[bupr_display_top_members]` — leaderboard by aggregate rating / review count.
- `[wbcom_admin_setting_header]` — shared-admin header nav (admin-only).

## 6. Content types
- CPT `review` (admin/bupr-admin.php) — `create_posts => do_not_allow`, supports title/editor/author.
- Taxonomy `review_category` (`show_ui => false`), seeded term `BP Member`.

## 7. Widgets (2)
- `bupr_single_member_rating_widget` (includes/widgets/member-rating.php)
- `bupr_members_review_setting` (includes/widgets/display-review.php)

## 8. JS / CSS modules
- `assets/js/bupr-front.js` — submit/edit/update/owner-reply/flag/star-rating. Localized
  `mail_title` with `review_nonce`. Uses native `alert()` x3 (FINDING — admin-ux Rule 10).
- `admin/assets/js/bupr-admin.js`, `bupr-color-picker.js`, `wbcom/.../wbcom-admin-setting.js`.
- CSS: `bupr-front.css`, `bupr-reviews.css`, FontAwesome, `bupr-admin.css`, `wbcom-admin-setting.css`.
  5 distinct breakpoints (FINDING — responsive Rule 1).

## 9. Email templates
No template files — inline HTML strings in `bupr-ajax.php::send_review_notifications`
and `bupr-admin.php::bupr_generate_activity_review_status_change`. Merge tags:
`[user-name] [reviewer-name] [review-link] [site-name] [site-admin] [review-aproval-link]`.

## 10. Cron jobs (1)
`bupr_cron_recalculate_user_reviews_batch` — batch recalc of `bupr_review_count` +
`bupr_aggregate_rating` (50 users/run, cursor `bupr_current_batch`). Scheduled with
conflicting intervals (daily on activation vs hourly on `wp`) — FINDING #2.

## 11. DB tables
_None_ — uses CPT `review` + user/post meta only.

## 12. Integrations
- BuddyPress / BuddyBoss (required), Nouveau + Legacy + Youzify + bp-rewrites + bp-classic compat.
- GamiPress (`gamipress_bp_member_review` trigger, `gamipress_activity_triggers` /
  `gamipress_trigger_get_user_id` filters).
- WPML (`wpml-config.xml`, `bupr_site_url` translation).
- Pro addon via `bupr_global_settings`, `bupr_admin_settings_tabs`, `bupr_after_rating_fields`,
  `bupr_before_save_review`, `bupr_aggregate_rating`, `bupr_shortcode_show_stars`, etc.

## Top findings
1. **(high)** `bupr_update_review` (bupr-ajax.php:665) — nonce-only, no ownership/cap check before `wp_update_post`.
2. **(medium)** Cron scheduled with conflicting intervals (daily vs hourly) in two places.
3. **(medium)** `alert()` x3 in bupr-front.js + missing `templates/wbcom-themes-page.php` include target.
