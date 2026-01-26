# Database Schema

Reference for all data stored by BuddyPress Member Reviews.

## Custom Post Type

### Reviews CPT

| Property | Value |
|----------|-------|
| Post Type | `review` |
| Slug | `review` |
| Supports | title, editor, author |
| Public | false |
| Show in Admin | true |

### Taxonomy

| Property | Value |
|----------|-------|
| Taxonomy | `review_category` |
| Hierarchical | true |
| Object Type | `review` |

## Post Meta

Stored on each review post (`review` post type).

### Core Review Data

| Meta Key | Type | Description |
|----------|------|-------------|
| `linked_bp_member` | int | User ID of the member being reviewed |
| `profile_star_rating` | array | Rating values per criterion |

**profile_star_rating format:**

```php
// With multiple criteria enabled
array(
    'Communication' => 5,
    'Quality' => 4,
    'Timeliness' => 5,
)

// Without criteria (single rating)
array( 5 )

// Pro flexible criteria (by ID)
array(
    'communication' => 5,
    'quality' => 4,
)
```

### Anonymous Reviews

| Meta Key | Type | Description |
|----------|------|-------------|
| `bupr_anonymous_review_post` | string | `'yes'` if anonymous, empty otherwise |

### Owner Replies

| Meta Key | Type | Description |
|----------|------|-------------|
| `bupr_owner_reply` | string | Reply content (HTML allowed) |
| `bupr_owner_reply_date` | int | Unix timestamp of reply |

### Flagging System

| Meta Key | Type | Description |
|----------|------|-------------|
| `bupr_flags` | array | Array of flag reports |
| `bupr_flag_status` | string | `'none'`, `'flagged'`, `'resolved'`, `'dismissed'` |
| `bupr_flag_count` | int | Total number of flags |
| `bupr_auto_hidden` | bool | True if auto-hidden due to threshold |
| `bupr_auto_hidden_date` | int | Unix timestamp when auto-hidden |

**bupr_flags format:**

```php
array(
    array(
        'reporter_id' => 123,
        'reason' => 'spam',
        'reason_label' => 'Spam or fake review',
        'date' => 1706234567,
    ),
    array(
        'reporter_id' => 456,
        'reason' => 'harassment',
        'reason_label' => 'Harassment or abuse',
        'date' => 1706234789,
    ),
)
```

### Pro Addon Meta

| Meta Key | Type | Description |
|----------|------|-------------|
| `bupr_verified` | bool | Whether review is verified (Pro) |
| `bupr_verified_type` | string | `'admin'`, `'tenure'`, `'transaction'` |
| `bupr_verified_date` | int | Unix timestamp of verification |
| `bupr_verified_by` | int | Admin user ID who verified |
| `bupr_custom_criteria` | array | Non-star criteria values (thumbs, text) |
| `bupr_request_message` | string | Endorsement request message |

## User Meta

Stored on each user who has received reviews.

| Meta Key | Type | Description |
|----------|------|-------------|
| `bupr_review_count` | int | Number of published reviews received |
| `bupr_aggregate_rating` | float | Average rating (1.0 - 5.0) |

**Calculation:** The aggregate rating is the average of all individual review averages. Each review's average is calculated from its criteria ratings.

## Options

Settings stored in `wp_options`.

### General Settings

Option: `bupr_admin_general_options`

```php
array(
    'bupr_multi_reviews' => 'yes|no',
    'bupr_hide_review_button' => 'yes|no',
    'bupr_auto_approve_reviews' => 'yes|no',
    'bupr_member_dir_reviews' => 'yes|no',
    'bupr_allow_email' => 'yes|no',
    'bupr_allow_notification' => 'yes|no',
    'bupr_allow_update' => 'yes|no',
    'bupr_exc_member' => array('subscriber', 'editor'),
    'bupr_add_member' => array('author'),
    'bupr_enable_anonymous_reviews' => 'yes|no',
    'bupr_enable_review_activity' => 'yes|no',
    'bupr_enable_owner_replies' => 'yes|no',
    'bupr_enable_flagging' => 'yes|no',
    'bupr_flag_threshold' => 5,
    'profile_reviews_per_page' => 10,
    'review_email_subject' => 'You Have Received a New Review',
    'review_email_message' => '...',
)
```

### Display Settings

Option: `bupr_admin_display_options`

```php
array(
    'bupr_review_title' => 'Review',
    'bupr_review_title_plural' => 'Reviews',
    'bupr_star_color' => '#ffb900',
)
```

### Criteria Settings

Option: `bupr_admin_settings`

```php
array(
    'profile_multi_rating_allowed' => '1|0',
    'rating_field_name' => array(
        'random_key_1' => 'Communication',
        'random_key_2' => 'Quality',
        'random_key_3' => 'Timeliness',
    ),
)
```

### Pro Settings

Option: `bupr_pro_review_mode`

```
'ratings' | 'testimonials' | 'endorsements'
```

Option: `bupr_pro_rating_criteria` (Pro flexible criteria)

```php
array(
    array(
        'id' => 'communication',
        'label' => 'Communication',
        'type' => 'stars',
        'required' => true,
        'weight' => 1,
    ),
    array(
        'id' => 'recommend',
        'label' => 'Would Recommend',
        'type' => 'thumbs',
        'required' => false,
        'weight' => 0.5,
    ),
)
```

## Database Queries

### Get All Reviews for a Member

```php
$reviews = get_posts( array(
    'post_type' => 'review',
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => 'linked_bp_member',
            'value' => $user_id,
            'compare' => '=',
        ),
    ),
    'posts_per_page' => -1,
) );
```

### Get Member's Aggregate Rating

```php
$rating = get_user_meta( $user_id, 'bupr_aggregate_rating', true );
$count = get_user_meta( $user_id, 'bupr_review_count', true );
```

### Get Flagged Reviews

```php
$flagged = get_posts( array(
    'post_type' => 'review',
    'meta_query' => array(
        array(
            'key' => 'bupr_flag_status',
            'value' => 'flagged',
        ),
    ),
) );
```

### Get Reviews by Author

```php
$reviews = get_posts( array(
    'post_type' => 'review',
    'author' => $reviewer_id,
    'post_status' => 'any',
) );
```

## Data Cleanup

### On Plugin Uninstall

The plugin removes:

- All `review` posts
- All `review_category` terms
- Options: `bupr_admin_general_options`, `bupr_admin_display_options`, `bupr_admin_settings`
- User meta: `bupr_review_count`, `bupr_aggregate_rating`

### Manual Cleanup Query

```sql
-- Delete all review posts
DELETE FROM wp_posts WHERE post_type = 'review';

-- Delete review post meta
DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts);

-- Delete user meta
DELETE FROM wp_usermeta WHERE meta_key IN ('bupr_review_count', 'bupr_aggregate_rating');

-- Delete options
DELETE FROM wp_options WHERE option_name LIKE 'bupr_%';
```

## Performance Considerations

### Indexes

The plugin relies on WordPress default indexes. For large sites, consider adding:

```sql
-- Index for member lookup
CREATE INDEX idx_linked_bp_member ON wp_postmeta (meta_key, meta_value(10))
WHERE meta_key = 'linked_bp_member';
```

### Aggregate Rating Updates

Ratings are recalculated:

- When a review is published
- When a review is trashed/deleted
- When a review's rating is edited
- When triggered via the `bupr_recalculate_member_reviews` function

For bulk operations, ratings update per-review. Consider using `bupr_recalculate_user_reviews_batch()` for large batches.
