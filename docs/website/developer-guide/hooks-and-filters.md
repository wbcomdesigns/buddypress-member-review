# Hooks and Filters

A reference of the actions and filters the free plugin fires. Signatures below are taken from the shipped code. The prefix throughout is `bupr_`, and the text domain is `bp-member-reviews`.

The free plugin has no REST API and no custom database tables. It stores reviews as a `review` custom post type; see [Data Model](data-model.md).

## Review lifecycle actions

### `bupr_before_save_review`

Fires just before a submitted review is written. Args: `int $review_id`, `array $add_review_args`.

```php
add_action( 'bupr_before_save_review', function ( $review_id, $args ) {
    // Inspect or extend the review before it is finalized.
}, 10, 2 );
```

### `bupr_member_review_after_review_insert`

Fires after a new review is created. Args: `int $review_id`, `int $member_id` (the reviewed member).

```php
add_action( 'bupr_member_review_after_review_insert', function ( $review_id, $member_id ) {
    // React to a new review.
}, 10, 2 );
```

### `bupr_sent_review_notification`

Fires after the review notification is sent. Args: `int $member_id`, `int $review_id`.

### `bupr_owner_reply_added`

Fires after a profile owner replies to a review. Args: `int $review_id`, `int $owner_id`, `int $reviewer_id`.

### `bupr_review_flagged`

Fires after a member reports a review. Args: `int $review_id`, `int $reporter_id`, `string $reason`.

### `bupr_recalculation_completed`

Fires after the daily batch recalculation finishes. No args.

## Template hooks

These fire inside the profile review templates. See [Template Overrides](templates.md).

- `bupr_before_member_review_list` (action, no args): before the review list.
- `bupr_after_member_review_list` (action, no args): after the review list.
- `bupr_after_review_date` (action, `int $review_id`): after a review's date.
- `bupr_after_review_content` (action, `int $review_id`): after a review's content.
- `bupr_after_rating_fields` (action, no args): after the rating fields in the review form.
- `bupr_reviewer_name_html` (filter, `string $html`, `int $review_id`): the reviewer name markup.

## Profile tab and label filters

- `bupr_show_profile_review_tab` (filter, `bool $show`): show or hide the Reviews tab per profile.
- `bupr_review_tab_name` (filter, `string $tab_name`): the plural tab label.
- `bupr_profile_review_singular_tab_name` (filter, `string $tab_name`): the singular label.
- `bupr_review_tab_plural_slug` / `bupr_review_tab_singular_slug` (filter, `string $slug`): the tab URL slugs.
- `bupr_default_subnav_slug` (filter, `string $slug`): the default subnav slug.

```php
add_filter( 'bupr_show_profile_review_tab', function ( $show ) {
    // Return false to hide the Reviews tab in some context.
    return $show;
} );
```

## Rating and settings filters

- `bupr_global_settings` (filter, `array $bupr`): the hydrated settings array. This is the primary seam the companion Pro addon uses.
- `bupr_aggregate_rating` (filter, `float $rating`, `int $user_id`, `array $review_ids`, `int $rated_count`): the computed aggregate before it is cached.
- `bupr_allow_activity_posting` (filter, `bool $allow`, `int $review_id`, `int $reviewed_user_id`): gate activity posting per review.
- `bupr_should_load_assets` (filter, `bool $load`): control front-end asset loading.
- `bupr_add_review_notification_format` (filter, `array|string $return`, `int $user_id`, `string $format`): the BuddyPress notification text.

## Shortcode and widget filters

The `[bupr_display_top_members]` shortcode exposes a family of filters for its query and output, including `bupr_shortcode_title`, `bupr_shortcode_member_limit`, `bupr_shortcode_type`, `bupr_shortcode_avatar`, `bupr_shortcode_meta_key`, `bupr_shortcode_query_results`, `bupr_shortcode_show_stars`, `bupr_shortcode_rating_text`, `bupr_shortcode_count_text`, and `bupr_shortcode_output`. The widgets expose `bupr_widget_show_stars` (filter, `bool $show`) and `bupr_item_type_label` (filter, `string $label`).

## Template loader filter

- `bupr_locate_template` (filter, `string $template`, `string $template_name`, `string $template_path`, `string $default_path`): the resolved template path. See [Template Overrides](templates.md).

## Admin tab filters

- `bupr_admin_panel_tabs` (filter, `array $tabs`): the card-panel tab descriptors (label, icon, group).
- `bupr_admin_settings_tabs` (filter, `array $map`): a legacy-shaped `slug => label` map kept for back-compat. Tabs absent from the returned map are removed.
