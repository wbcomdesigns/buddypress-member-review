# Code Flows — BuddyPress Member Reviews 3.7.1

Derived from `audit/manifest.json`. Trace: trigger -> PHP -> AJAX -> JS -> output.

## Bootstrap
`buddypress-member-review.php` -> `plugins_loaded` -> `bupr_plugins_files()`
(guards: `bupr_check_buddypress_compatibility()` BP>=6.0/BB>=1.8 + members component;
`bupr_is_plugin_active()`) -> includes 12 files -> each instantiates its class.
`init`: `BUPRGlobals::bupr_review_globals()` hydrates global `$bupr` from 3 option arrays,
then fires `bupr_global_settings` (Pro hook).

## Flow A — Submit a review (public)
1. Profile "Add Review" subnav -> `bupr_reviews_form_tab_function_to_show_screen` ->
   `bupr_member_review_form` action -> `BUPR_Shortcodes::bupr_display_review_form` renders form
   (member select, star criteria from `$bupr['active_rating_fields']`, nonce `save-bp-member-review`).
2. `assets/js/bupr-front.js` (#bupr_save_review) -> POST `allow_bupr_member_review_update`
   with `review-nonce`.
3. `BUPR_AJAX::wp_allow_bupr_my_member` (bupr-ajax.php:155) — verify nonce, block self-review,
   `wp_insert_post` CPT `review` (status = publish if `auto_approve_reviews`, else draft),
   set `linked_bp_member` + `profile_star_rating` meta, fire `bupr_before_save_review`,
   `bupr_member_review_after_review_insert` (if publish), then `send_review_notifications`.
4. Listeners: `bupr_create_review_activity` (BP activity) + `bupr_handle_review_submission`
   -> `bupr_recalculate_user_reviews_for_user` (updates aggregate usermeta).
5. `bupr_sent_review_notification` -> `bupr_buddypress_add_notification`.

Key files: bupr-shortcodes.php, bupr-front.js, bupr-ajax.php, bupr-filters.php,
bupr-general-functions.php, bupr-notification.php.

## Flow B — Edit / update own review
1. `bupr_after_member_review_list` -> `bupr_edit_review_form_modal` renders modal template.
2. JS POST `bupr_edit_review` -> `BUPR_AJAX::bupr_edit_review` (ownership/cap check at :538) -> returns form HTML.
3. JS POST `bupr_update_review` -> `BUPR_AJAX::bupr_update_review` (bupr-ajax.php:665) ->
   `wp_update_post` + `profile_star_rating` -> `bupr_member_review_after_review_insert` (recalc).
   **AUTH GAP:** step 3 verifies nonce only — no ownership/cap check (FINDING #1).

## Flow C — Approve review (admin)
Reviews list row action "Approve" (draft) -> JS POST `bupr_approve_review`
(`bupr_member_review_ajax` nonce) -> `BUPR_AJAX::bupr_approve_review` (`edit_posts`) ->
publish -> GamiPress + recalc.

## Flow D — Owner reply
Profile owner inline reply -> JS POST `bupr_add_owner_reply` -> `BUPR_AJAX::bupr_add_owner_reply`
(gated by `enable_owner_replies`; owner OR manage_options; one reply max) -> stores
`bupr_owner_reply` meta -> `bupr_owner_reply_added` -> returns reply HTML.
JS error paths use native `alert()` (bupr-front.js:442/466/472 — FINDING).

## Flow E — Flag / report
JS POST `bupr_flag_review` -> `BUPR_AJAX::bupr_flag_review` (gated by `enable_flagging`;
logged-in; one flag/user) -> append `bupr_flags`, bump `bupr_flag_count`, set
`bupr_flag_status=flagged`; auto-hide to draft when count >= `flag_threshold` ->
`bupr_review_flagged`. Admin resolves via review edit meta box -> admin-post
`bupr_dismiss_flag` / `bupr_resolve_flag` (per-post nonce + `edit_post`).

## Flow F — Aggregate recalculation (cron)
`bupr_cron_recalculate_user_reviews_batch` -> `bupr_recalculate_user_reviews_batch(50, paged)`
-> per user `bupr_recalculate_user_reviews_for_user` (direct SQL over `review` posts +
`profile_star_rating`) -> update `bupr_review_count` + `bupr_aggregate_rating` usermeta.
Cursor in `bupr_current_batch`. Also invalidated on post status transition / delete
(`bupr_handle_review_status_transition`, `bupr_clear_review_caches`).
**FINDING #2:** scheduled daily (activation) AND hourly (on `wp`).

## Flow G — Admin settings
`admin.php?page=bp-member-review-settings` -> `BUPR_Admin::bupr_admin_options_page` ->
`[wbcom_admin_setting_header]` (OLD wrapper nav) + `bupr_plugin_settings_tabs()`
(Welcome/General/Criteria/Display/Pro Features; filter `bupr_admin_settings_tabs`) ->
`settings_fields($tab)` + `do_settings_sections($tab)`. Saves to the 3 `bupr_admin_*`
option arrays via `BUPR_Admin::bupr_sanitize_options` (+ `pre_update_option_bupr_admin_settings`).

## Permissions summary
- Submit review: any logged-in (or guest via nopriv); self-review blocked.
- Edit own review: author OR `edit_others_posts`.
- Update review: nonce only (GAP).
- Approve: `edit_posts`. Settings/menus: `manage_options`. Flag resolve: `edit_post`.
