# Database Schema

BuddyPress Member Reviews uses WordPress custom post types, taxonomies, and meta fields to store review data.

## Custom Post Type

### Post Type: `review`

Reviews are stored as a custom post type with these properties:

| Field | Description |
|-------|-------------|
| `post_type` | Always `review` |
| `post_author` | Reviewer user ID |
| `post_content` | Review text content |
| `post_status` | `publish`, `pending`, `draft` |
| `post_date` | Review submission date |
| `post_title` | Auto-generated or empty |

**Query Example:**

```php
$reviews = get_posts( array(
    'post_type'      => 'review',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'meta_key'       => 'linked_bp_member',
    'meta_value'     => 123, // Member ID
) );
```

---

## Taxonomy

### Taxonomy: `review_category`

Review categories for filtering and organization.

**Note:** The review_category taxonomy is registered but not currently exposed in the admin UI. It is available for developers to use programmatically.

**Query Example:**

```php
$terms = get_terms( array(
    'taxonomy'   => 'review_category',
    'hide_empty' => false,
) );
```

---

## Post Meta Keys

All meta keys are stored in the `wp_postmeta` table with `post_id` referencing the review post.

### Core Meta

#### `linked_bp_member`

**Type:** `int`
**Description:** The reviewed member's user ID
**Example:**

```php
$member_id = get_post_meta( $review_id, 'linked_bp_member', true );
// Returns: 123
```

#### `profile_star_rating`

**Type:** `array` (serialized)
**Description:** Rating values for each criteria
**Format:**

```php
array(
    'quality'        => 4,
    'communication'  => 5,
    'professionalism'=> 4,
    'recommendation' => 5
)
```

**Example:**

```php
$ratings = get_post_meta( $review_id, 'profile_star_rating', true );
$quality_rating = isset( $ratings['quality'] ) ? $ratings['quality'] : 0;
```

#### `bupr_anonymous_review_post`

**Type:** `string` (`'yes'` or `'no'`)
**Description:** Whether review is anonymous
**Example:**

```php
$is_anonymous = get_post_meta( $review_id, 'bupr_anonymous_review_post', true );
if ( $is_anonymous === 'yes' ) {
    // Hide reviewer identity
}
```

---

### Owner Reply Meta

#### `bupr_owner_reply`

**Type:** `string`
**Description:** Member's reply to the review
**Example:**

```php
$reply = get_post_meta( $review_id, 'bupr_owner_reply', true );
// Returns: "Thank you for your feedback!"
```

#### `bupr_owner_reply_date`

**Type:** `int` (Unix timestamp)
**Description:** When owner replied
**Example:**

```php
$reply_date = get_post_meta( $review_id, 'bupr_owner_reply_date', true );
echo date( 'F j, Y', $reply_date );
// Returns: "January 15, 2026"
```

---

### Flagging & Moderation Meta

#### `bupr_flags`

**Type:** `array` (serialized)
**Description:** Array of user IDs who flagged this review
**Format:**

```php
array( 45, 67, 89 )
```

**Example:**

```php
$flags = get_post_meta( $review_id, 'bupr_flags', true );
$flag_count = is_array( $flags ) ? count( $flags ) : 0;
```

#### `bupr_flag_status`

**Type:** `string` (`'none'`, `'flagged'`, `'resolved'`, `'dismissed'`)
**Description:** Current flag status
**Example:**

```php
$status = get_post_meta( $review_id, 'bupr_flag_status', true );
if ( $status === 'flagged' ) {
    // Show admin notice
}
```

#### `bupr_flag_count`

**Type:** `int`
**Description:** Total times review was flagged
**Example:**

```php
$count = get_post_meta( $review_id, 'bupr_flag_count', true );
// Returns: 3
```

#### `bupr_auto_hidden`

**Type:** `boolean`
**Description:** Whether review was auto-hidden by threshold
**Example:**

```php
$auto_hidden = get_post_meta( $review_id, 'bupr_auto_hidden', true );
```

#### `bupr_auto_hidden_date`

**Type:** `int` (Unix timestamp)
**Description:** When review was auto-hidden
**Example:**

```php
$hidden_date = get_post_meta( $review_id, 'bupr_auto_hidden_date', true );
```

---

## User Meta Keys

Stored in `wp_usermeta` table with `user_id` referencing the reviewed member.

### Core User Meta

#### `bupr_review_count`

**Type:** `int`
**Description:** Total published reviews for member
**Example:**

```php
$review_count = get_user_meta( $user_id, 'bupr_review_count', true );
// Returns: 15
```

#### `bupr_aggregate_rating`

**Type:** `float`
**Description:** Member's overall aggregate rating
**Calculation:** Average of all criteria ratings across all published reviews
**Example:**

```php
$aggregate = get_user_meta( $user_id, 'bupr_aggregate_rating', true );
// Returns: 4.6
```

**Calculation Logic:**

```php
// For each review:
// 1. Calculate review average: sum(all criteria) / count(criteria)
// 2. Calculate member aggregate: sum(all review averages) / total reviews

// Example with 2 reviews:
// Review 1: quality=5, communication=4, avg=4.5
// Review 2: quality=4, communication=5, avg=4.5
// Aggregate: (4.5 + 4.5) / 2 = 4.5
```

---

## Pro-Specific Meta

Available only with BuddyPress Member Reviews Pro.

### Verification Meta

#### `bupr_verified`

**Type:** `string` (`'yes'` or `'no'`)
**Description:** Whether review is verified
**Example:**

```php
$is_verified = get_post_meta( $review_id, 'bupr_verified', true );
```

#### `bupr_verified_type`

**Type:** `string`
**Description:** Verification method
**Values:** `'purchase'`, `'connection'`, `'admin'`, `'custom'`
**Example:**

```php
$type = get_post_meta( $review_id, 'bupr_verified_type', true );
// Returns: "purchase"
```

#### `bupr_verified_date`

**Type:** `int` (Unix timestamp)
**Description:** When review was verified
**Example:**

```php
$verified_date = get_post_meta( $review_id, 'bupr_verified_date', true );
```

---

### Endorsement Meta

#### `bupr_endorsed_skills`

**Type:** `array` (serialized)
**Description:** Skills endorsed in review
**Format:**

```php
array( 'WordPress', 'PHP', 'JavaScript' )
```

**Example:**

```php
$skills = get_post_meta( $review_id, 'bupr_endorsed_skills', true );
if ( is_array( $skills ) ) {
    foreach ( $skills as $skill ) {
        echo '<span class="skill-tag">' . esc_html( $skill ) . '</span>';
    }
}
```

#### `bupr_endorsement_status`

**Type:** `string` (`'accepted'`, `'pending'`, `'rejected'`)
**Description:** Endorsement acceptance status
**Example:**

```php
$status = get_post_meta( $review_id, 'bupr_endorsement_status', true );
```

#### `bupr_request_message`

**Type:** `string`
**Description:** Message from review requester
**Example:**

```php
$message = get_post_meta( $review_id, 'bupr_request_message', true );
// Returns: "Please review our collaboration on Project X"
```

---

## Data Format Examples

### Complete Review Post

```php
// Post data
$review = array(
    'ID'           => 456,
    'post_type'    => 'review',
    'post_author'  => 123,  // Reviewer
    'post_content' => 'Excellent work on the project...',
    'post_status'  => 'publish',
    'post_date'    => '2026-01-15 10:30:00'
);

// Post meta
$meta = array(
    'linked_bp_member' => 789,  // Reviewed member
    'profile_star_rating' => array(
        'quality'         => 5,
        'communication'   => 4,
        'professionalism' => 5,
        'recommendation'  => 5
    ),
    'bupr_anonymous_review_post' => 'no',
    'bupr_owner_reply'           => 'Thank you!',
    'bupr_owner_reply_date'      => 1705320000,
    'bupr_flags'                 => array(),
    'bupr_flag_count'            => 0,
    'bupr_flag_status'           => '',

    // Pro fields
    'bupr_verified'              => 'yes',
    'bupr_verified_type'         => 'connection',
    'bupr_verified_date'         => 1705320000,
    'bupr_endorsed_skills'       => array( 'WordPress', 'PHP' ),
    'bupr_endorsement_status'    => 'accepted'
);
```

---

## Database Queries

### Get All Reviews for Member

```php
global $wpdb;

$member_id = 123;
$reviews = $wpdb->get_results( $wpdb->prepare(
    "SELECT p.* FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'review'
    AND p.post_status = 'publish'
    AND pm.meta_key = 'linked_bp_member'
    AND pm.meta_value = %d
    ORDER BY p.post_date DESC",
    $member_id
) );
```

### Calculate Aggregate Rating

```php
function calculate_aggregate_rating( $member_id ) {
    $reviews = get_posts( array(
        'post_type'      => 'review',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'linked_bp_member',
        'meta_value'     => $member_id,
    ) );

    if ( empty( $reviews ) ) {
        return 0;
    }

    $total = 0;
    foreach ( $reviews as $review ) {
        $ratings = get_post_meta( $review->ID, 'profile_star_rating', true );
        if ( is_array( $ratings ) && ! empty( $ratings ) ) {
            $review_avg = array_sum( $ratings ) / count( $ratings );
            $total += $review_avg;
        }
    }

    $aggregate = $total / count( $reviews );

    // Update user meta
    update_user_meta( $member_id, 'bupr_aggregate_rating', $aggregate );
    update_user_meta( $member_id, 'bupr_review_count', count( $reviews ) );

    return $aggregate;
}
```

### Get Flagged Reviews

```php
$flagged_reviews = get_posts( array(
    'post_type'      => 'review',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'bupr_flag_status',
            'value'   => 'flagged',
            'compare' => '='
        )
    )
) );
```

---

## Data Retention

When a review is deleted:

- Post and all post meta are removed
- User meta (`bupr_review_count`, `bupr_aggregate_rating`) is recalculated
- Associated activities (if enabled) are deleted
- Notifications are removed

---

## Related Documentation

- [Hooks & Filters](hooks-and-filters.md)
- [REST API Reference](rest-api.md)
- [Template Overrides](templates.md)
