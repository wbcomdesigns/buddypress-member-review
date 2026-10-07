# Hooks and Filters

This is a reference of the actions and filters the free plugin fires, checked against version 3.8.1. Every name starts with `bupr_` (except the GamiPress and shared hub hooks noted below). The text domain is `bp-member-reviews`.

The plugin has no REST API of its own and no custom database tables. Reviews are a `review` post type. See [Data Model](data-model.md).

## Actions

### Review lifecycle

| Action | Arguments | When it fires |
|---|---|---|
| `bupr_before_save_review` | `int $review_id`, `array $add_review_args` | Right after a new review post is inserted and its meta saved, before notifications are sent. Used by the Pro add-on to save extra fields. |
| `bupr_member_review_after_review_insert` | `int $review_id`, `int $reviewed_user_id` | After a review is published: on submit when auto-approved, on every edit, and when a draft changes to published. The plugin uses it to recalculate the member's rating and to create the activity entry. |
| `bupr_sent_review_notification` | `int $recipient_id`, `int $review_id` | Asks for a BuddyPress notification to be created for the recipient (the reviewed member, or the first administrator for a pending review). The email is not sent through this action. |
| `bupr_owner_reply_added` | `int $review_id`, `int $owner_id`, `int $reviewer_id` | After an owner reply is saved. The plugin uses it to send the reviewer a BuddyPress notification; add your own listener for email or other channels. |
| `bupr_review_flagged` | `int $review_id`, `int $reporter_id`, `string $reason` | After a report is saved. `$reason` is `spam`, `harassment`, `inappropriate` or `conflict`. The plugin uses it to send the site administrator a BuddyPress notification; add your own listener to alert your team another way. |
| `bupr_recalculation_completed` | none | After the daily batch has gone through every member. The plugin uses it to flush the top-members cache. |
| `gamipress_bp_member_review` | `int $user_id` | Fired for the review writer when a review is published, so GamiPress can award points. The plugin also registers the matching GamiPress trigger. |

```php
add_action( 'bupr_review_flagged', function ( $review_id, $reporter_id, $reason ) {
	wp_mail( get_option( 'admin_email' ), 'Review reported', get_edit_post_link( $review_id, 'raw' ) );
}, 10, 3 );
```

### Template and form hooks

| Action | Arguments | Where |
|---|---|---|
| `bupr_member_review_form` | none | Renders the review form. The plugin hooks its own form here. |
| `bupr_before_member_review_list` | none | Before the list in `bupr-reviews-tab-template.php`. |
| `bupr_after_member_review_list` | none | After the list. The edit-review form is added here. Keep this call if you override the template. |
| `bupr_after_review_date` | `int $review_id` | After a review's date, in the list and on the single review page. |
| `bupr_after_review_content` | `int $review_id` | After a review's content in the list. |
| `bupr_after_rating_fields` | none | After the star fields in the review form. |

### Admin hooks

| Action | Arguments | Where |
|---|---|---|
| `bupr_admin_settings_criteria_content` | none | On the **Criteria** tab. If a callback prints anything here, the free criteria form is skipped and your output is used. The Pro add-on uses this. |

## Filters

### Settings and tabs

| Filter | Arguments | Purpose |
|---|---|---|
| `bupr_global_settings` | `array $bupr` | The merged settings array every part of the plugin reads. Change values here to override a setting in code. |
| `bupr_show_profile_review_tab` | `bool $show` | Return `false` to hide the **Reviews** tab. |
| `bupr_review_tab_name` | `string $name` | The plural name used for the tab. |
| `bupr_profile_review_singular_tab_name` | `string $name` | The singular name. |
| `bupr_review_tab_plural_slug` / `bupr_review_tab_singular_slug` | `string $slug` | The tab and "Add Review" slugs. |
| `bupr_default_subnav_slug` | `string $slug` | The slug of the review list sub-tab. Default `view`. |
| `bupr_should_load_assets` | `bool $load` | Return `true` to load the front-end CSS and JS on pages the plugin would skip. |

### Ratings, activity and notifications

| Filter | Arguments | Purpose |
|---|---|---|
| `bupr_aggregate_rating` | `float $rating`, `int $user_id`, `array $review_ids`, `int $rated_count` | The member's average before it is saved. |
| `bupr_allow_activity_posting` | `bool $allow`, `int $review_id`, `int $reviewed_user_id` | Return `false` to skip the activity entry for a review. |
| `bupr_member_review_activity_action` | `string $format` | The `sprintf` format of the activity line. `%1$s` reviewer, `%2$s` label, `%3$s` reviewed member. |
| `bupr_hide_username_in_activity` | `string $action` | Final activity text for anonymous reviews. |
| `bupr_change_user_link` | `string $link` | Link used for an anonymous reviewer in activity. |
| `bupr_change_avatar_image` | `string $html` | Avatar markup for an anonymous reviewer in activity. |
| `bupr_change_default_avatar` | `string $style` | Gravatar style for anonymous avatars. Default `mystery`. |
| `bupr_default_avatar_size` | `int $size` | Size in pixels. Default 150. |
| `bupr_add_review_notification_format` | `array\|string $return`, `int $user_id`, `string $format` | The BuddyPress notification text and link. |

### Review display and forms

| Filter | Arguments | Purpose |
|---|---|---|
| `bupr_reviewer_name_html` | `string $html`, `int $review_id` | The reviewer name markup, in the list and single page. |
| `bupr_edit_review_extra_fields` | `string $html`, `int $review_id` | Extra HTML placed before the **Update Review** button in the edit form. |
| `bupr_member_query_args` | `array $args` | `WP_User_Query` arguments for the member dropdown on the standalone review form. Default: exclude the current user, 100 members. |
| `bupr_member_list` | `array $members` | The member list for that dropdown. |
| `bupr_locate_template` | `string $template`, `string $template_name`, `string $template_path`, `string $default_path` | The final template file path. See [Template Overrides](templates.md). |

### Top members shortcode

| Filter | Arguments |
|---|---|
| `bupr_display_top_members_shortcode_atts` | `array $defaults`, `array $attrs` |
| `bupr_shortcode_title`, `bupr_shortcode_member_limit`, `bupr_shortcode_type`, `bupr_shortcode_avatar` | the attribute value |
| `bupr_shortcode_meta_key` | `string $meta_key`, `string $type` |
| `bupr_shortcode_query_results` | `array $rows`, `string $meta_key` |
| `bupr_shortcode_member_id` | `int $user_id` |
| `bupr_shortcode_aggregate_rating` | `float $rating`, `int $user_id` |
| `bupr_shortcode_review_count` | `int $count`, `int $user_id` |
| `bupr_shortcode_avatar_display` | `string $html`, `int $user_id` |
| `bupr_shortcode_show_stars` | `bool $show`, `int $user_id` |
| `bupr_shortcode_star_full` | `string $html`, `int $user_id`, `int $index` |
| `bupr_shortcode_star_half`, `bupr_shortcode_star_empty` | `string $html`, `int $user_id` |
| `bupr_shortcode_rating_text` | `string $html`, `int $user_id`, `float $rating`, `int $count` |
| `bupr_shortcode_count_text` | `string $html`, `int $user_id`, `int $count` |
| `bupr_shortcode_no_members_text` | `string $html` |
| `bupr_shortcode_output` | `string $html` |

The widgets share two filters with the shortcode: `bupr_widget_show_stars` (`bool $show`) and `bupr_item_type_label` (`string $label`, `int $count`; also used by the shortcode).

### Admin and shared hub

| Filter | Arguments | Purpose |
|---|---|---|
| `bupr_admin_panel_tabs` | `array $tabs` | The settings tabs (label, icon, group), keyed by slug. |
| `bupr_admin_settings_tabs` | `array $map` | A legacy `slug => label` map. A tab missing from the returned map is removed (the Pro add-on uses this to replace **Criteria**). **Discover** is added after this filter, so it cannot be removed here. |
| `wbcom_hub_wrapper_helper_slugs` | `string[] $slugs` | Pages hidden from the shared **WB Plugins** hub list. |

Tab slugs are `overview`, `bupr-general`, `bupr-criteria`, `bupr-display` and `discover`. They appear in `?page=bp-member-review-settings&tab=...`.

## AJAX actions

All are `wp_ajax_` (logged-in only) and expect a nonce.

| Action | Purpose | Who may call it |
|---|---|---|
| `allow_bupr_member_review_update` | Submit a new review. | Any logged-in member allowed by the role settings, not reviewing themselves. |
| `bupr_edit_review` | Load the edit form. | The review's writer, or a user who can `edit_others_posts`. Members are refused when **Update Review** is off. |
| `bupr_update_review` | Save an edited review. | Same as above. |
| `bupr_add_owner_reply` | Save an owner reply. | The reviewed member, or a user who can `manage_options`. Published reviews only. |
| `bupr_flag_review` | Save a report. | Any logged-in member, once per review. |
| `bupr_approve_review` | Publish a draft review from the admin list. | Users who can `edit_others_posts`. |

Report moderation uses `admin-post.php` actions `bupr_dismiss_flag` and `bupr_resolve_flag`, which also need `edit_others_posts` and a nonce.
