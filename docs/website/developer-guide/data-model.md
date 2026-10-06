# Data Model

The plugin adds no custom database tables. Reviews are stored as a WordPress custom post type with post meta, and each member's cached rating lives in user meta. Everything sits in the standard `wp_posts`, `wp_postmeta`, `wp_usermeta`, and `wp_options` tables.

## Custom post type: `review`

Each review is one `review` post.

| Field | Meaning |
|---|---|
| `post_author` | The reviewer's user ID. |
| `post_content` | The written review text. |
| `post_status` | `publish` (approved) or `draft` (pending or auto-hidden). |
| `post_date` | When the review was submitted. |

The reviewed member is not the author; it is stored in the `linked_bp_member` post meta.

```php
$reviews = get_posts( array(
    'post_type'      => 'review',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'meta_key'       => 'linked_bp_member',
    'meta_value'     => 123, // reviewed member ID
) );
```

## Taxonomy: `review_category`

A hierarchical taxonomy on the `review` post type. Its UI is not surfaced to admins; a `BP Member` term is seeded and assigned to profile reviews. It is available to developers programmatically.

## Post meta

| Key | Type | Meaning |
|---|---|---|
| `linked_bp_member` | int | The reviewed member's user ID. |
| `profile_star_rating` | array | Per-criterion star values, keyed by criterion identifier. |
| `bupr_anonymous_review_post` | string | `yes` if submitted anonymously. |
| `bupr_owner_reply` | string | The profile owner's reply text. |
| `bupr_owner_reply_date` | int | Owner reply timestamp. |
| `bupr_flags` | array | Report records, each with reporter ID, reason, reason label, and date. |
| `bupr_flag_count` | int | Number of reports. |
| `bupr_flag_status` | string | `none`, `flagged`, `resolved`, or `dismissed`. |
| `bupr_auto_hidden` | bool | Whether the review was auto-hidden at the report threshold. |
| `bupr_auto_hidden_date` | int | Auto-hide timestamp. |

The keys in `profile_star_rating` are criterion identifiers, not display labels. They stay stable when a criterion is renamed in the admin, which is why renaming a criterion does not break existing reviews.

```php
$ratings = get_post_meta( $review_id, 'profile_star_rating', true );
// e.g. array( 'Member Response' => 4, 'Member Skills' => 5 )
```

## User meta

Stored against the reviewed member and refreshed when reviews change or by the daily reconciliation job.

| Key | Type | Meaning |
|---|---|---|
| `bupr_review_count` | int | Cached count of published reviews received. |
| `bupr_aggregate_rating` | float | Cached average rating, 0 to 5. |
| `bupr_last_calculated` | int | Timestamp of the last recalculation. |

## Options

| Option | Meaning |
|---|---|
| `bupr_admin_general_options` | The General tab settings (array). |
| `bupr_admin_settings` | The criteria settings (array). |
| `bupr_admin_display_options` | The Display tab labels and star color (array). |
| `bupr_plugin_version` | Installed plugin version. |
| `bupr_current_batch` | Cursor for the daily recalculation cron. |
| `bupr_top_members_cache_ver` | Cache salt for the top-members shortcode transient. |
| `bupr_meta_repair_v1_done` / `bupr_meta_repair_paged` | One-time meta-repair migration gate and cursor. |

## How the aggregate is computed

For each published review, all its criterion values are averaged into a review score. All review scores are then averaged into the member's aggregate, so every review counts equally regardless of how many criteria it used. The result is written to `bupr_aggregate_rating` and the count to `bupr_review_count`, and can be adjusted via the `bupr_aggregate_rating` filter. See [Hooks and Filters](hooks-and-filters.md).

## When a review is deleted

Trashing or deleting a review recalculates the member's cached count and rating, removes the related BuddyPress notification, and removes the linked activity item if activity posting was enabled.
