# Data Model

The plugin creates no database tables. Reviews are posts of type `review`, each member's cached rating is user meta, and settings are options.

## Post type: `review`

| Property | Value |
|---|---|
| Public | No. `public` and `has_archive` are false, so reviews are left out of search, archives, feeds and sitemaps. A review's own URL (`/review/slug/`) redirects with a 301 to the review on the member's profile, and listing URLs return 404, so no page can show the author of an anonymous review. Reviews are read on member profiles. |
| Admin menu | **Reviews**, with the speech bubble icon. |
| Supports | Title, editor, author. |
| Add New | Disabled (`create_posts` is `do_not_allow`). Reviews are only created from the front end. |
| REST API | Not registered for the REST API. |

| Field | Meaning |
|---|---|
| `post_author` | The reviewer. |
| `post_title` | Generated: "{reviewed member name} received a {label}". |
| `post_content` | The written review. |
| `post_status` | `publish` (live) or `draft` (waiting for approval, or hidden by reports). Trash when removed. |

The reviewed member is not the author. They are stored in `linked_bp_member`.

```php
$reviews = get_posts( array(
	'post_type'      => 'review',
	'post_status'    => 'publish',
	'posts_per_page' => 10,
	'meta_key'       => 'linked_bp_member',
	'meta_value'     => 123, // reviewed member ID.
) );
```

## Taxonomy: `review_category`

Hierarchical, with no admin screen (`show_ui` is false) but shown as the **Reviews Category** column in the Reviews list. The plugin creates the term `BP Member` and gives it to every profile review. The BuddyPress Group Reviews plugin shares the same post type.

## Post meta on a review

| Key | Type | Meaning |
|---|---|---|
| `linked_bp_member` | int | The reviewed member's user ID. |
| `profile_star_rating` | array | Stars by criterion name, for example `array( 'Member Response' => 4 )`. With a single overall rating, a list with one number. |
| `bupr_anonymous_review_post` | string | `yes` or `no`. Saved only while **Anonymous Reviews** is on. |
| `bupr_owner_reply` | string | The owner reply text. |
| `bupr_owner_reply_date` | int | Reply timestamp. |
| `bupr_flags` | array | One entry per report: `reporter_id`, `reason`, `reason_label`, `date`. |
| `bupr_flag_count` | int | Total reports. |
| `bupr_flag_status` | string | `flagged`, `dismissed` or `resolved`. Absent if never reported. |
| `bupr_flag_dismissed_at_count` | int | The report count at the last dismissal. Auto-hide counts only reports above this number. |
| `bupr_auto_hidden` | bool | Set when the report threshold hid the review. Removed when the review is published again. |
| `bupr_auto_hidden_date` | int | When it was hidden. |

The keys in `profile_star_rating` are the criterion names as they were when the review was written. Renaming a criterion later does not change old reviews.

## User meta (on the reviewed member)

| Key | Type | Meaning |
|---|---|---|
| `bupr_review_count` | int | Published reviews received. |
| `bupr_aggregate_rating` | float | Average rating, 0 to 5. |

These are recalculated when a review is published, edited, hidden, trashed or deleted, by a daily cron event, and on demand when the value is missing.

## Options

| Option | Holds |
|---|---|
| `bupr_admin_general_options` | **General** tab settings. |
| `bupr_admin_settings` | **Criteria** tab settings: `profile_multi_rating_allowed` (`1` or `0`) and `profile_rating_fields` (criterion name => `yes`). |
| `bupr_admin_display_options` | **Display** tab settings: `bupr_review_title`, `bupr_review_title_plural`, `bupr_star_color`. |
| `bupr_plugin_version` | Installed version. |
| `bupr_current_batch` | Cursor for the daily recalculation. |
| `bupr_top_members_cache_ver` | Version stamp used to refresh the top-members cache. |
| `bupr_meta_repair_v1_done`, `bupr_meta_repair_paged` | One-time repair of stale rating counts. |

Settings you never saved have no entry in the option. The plugin then uses its built-in default (see the [Settings Reference](../usage/settings-reference.md)).

Temporary values (transients): `bupr_top_members_*` (cached shortcode results, 15 minutes) and `bupr_drift_checked_{user_id}` (once a day per member).

## Activity stream entries

When **Activity Stream** is on, a published review adds an entry in the Members component with type `member_review` (type `activity_status` when Youzify is active), `item_id` = the review ID and `secondary_item_id` = the reviewed member.

## BuddyPress notifications

Component `buddypress_member_review`, `item_id` = the review ID. Actions: `bupr_add_review_action` (new review, one per review per recipient), `bupr_review_flagged_action` (report, to the first administrator) and `bupr_owner_reply_action` (reply, to the reviewer); the last two add at most one unread notification per review per recipient. Trashing or deleting a review removes all of them.

## Cron

`bupr_cron_recalculate_user_reviews_batch` runs daily and recalculates 50 members per run, continuing where the last run stopped. It is removed when the plugin is deactivated.

## How the average is computed

For each published review, the numeric stars are averaged into a review score. The review scores are then averaged into the member's rating. Reviews with no stars add to the count but not the average. The result goes through the `bupr_aggregate_rating` filter before it is saved.

## When a review is deleted

- Trashing or deleting recalculates the member's count and rating.
- Its BuddyPress notifications are removed.
- Its activity entry is removed.

## On uninstall

Deleting the plugin does not remove reviews, ratings or settings. The `uninstall.php` file does nothing. The data stays in your database.
