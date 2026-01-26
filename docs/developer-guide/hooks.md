# Hooks Reference

Complete reference of actions and filters for customizing BuddyPress Member Reviews.

## Actions

### Review Lifecycle

#### `bupr_member_review_after_review_insert`

Fires after a new review is created.

```php
do_action( 'bupr_member_review_after_review_insert', $review_id, $member_id );
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$review_id` | int | The new review post ID |
| `$member_id` | int | User ID of the member being reviewed |

**Example: Send custom notification**
```php
add_action( 'bupr_member_review_after_review_insert', function( $review_id, $member_id ) {
    // Send to external service
    wp_remote_post( 'https://api.example.com/webhook', [
        'body' => [
            'event' => 'new_review',
            'review_id' => $review_id,
            'member_id' => $member_id,
        ]
    ]);
}, 10, 2 );
```

#### `bupr_sent_review_notification`

Fires after review notification is sent to the member.

```php
do_action( 'bupr_sent_review_notification', $member_id, $review_id );
```

#### `bupr_before_member_review_list`

Fires before the review list is rendered on a member profile.

```php
do_action( 'bupr_before_member_review_list' );
```

**Example: Add custom header**
```php
add_action( 'bupr_before_member_review_list', function() {
    echo '<div class="review-guidelines">';
    echo '<p>Reviews help our community stay trustworthy.</p>';
    echo '</div>';
});
```

#### `bupr_after_member_review_list`

Fires after the review list is rendered.

```php
do_action( 'bupr_after_member_review_list' );
```

#### `bupr_after_rating_fields`

Fires after the rating star fields in the review form. Used by Pro for custom criteria.

```php
do_action( 'bupr_after_rating_fields' );
```

**Example: Add custom field**
```php
add_action( 'bupr_after_rating_fields', function() {
    echo '<div class="custom-field">';
    echo '<label>Would you work with this person again?</label>';
    echo '<select name="work_again"><option value="yes">Yes</option><option value="no">No</option></select>';
    echo '</div>';
});
```

#### `bupr_after_review_date`

Fires after the review date is displayed. Used by Pro for verified badges.

```php
do_action( 'bupr_after_review_date', $review_id );
```

### Admin Hooks

#### `bupr_admin_settings_criteria_content`

Fires in the Criteria tab. Pro addon uses this to replace the default criteria builder.

```php
do_action( 'bupr_admin_settings_criteria_content' );
```

## Filters

### Display Filters

#### `bupr_show_profile_review_tab`

Control whether the Reviews tab appears on a member profile.

```php
apply_filters( 'bupr_show_profile_review_tab', $show, $user_id );
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$show` | bool | Whether to show the tab (default: true) |
| `$user_id` | int | The profile owner's user ID |

**Example: Hide tab for certain users**
```php
add_filter( 'bupr_show_profile_review_tab', function( $show, $user_id ) {
    // Hide reviews tab for administrators
    if ( user_can( $user_id, 'manage_options' ) ) {
        return false;
    }
    return $show;
}, 10, 2 );
```

#### `bupr_review_tab_name`

Change the name of the Reviews tab.

```php
apply_filters( 'bupr_review_tab_name', $name );
```

**Example: Dynamic tab name**
```php
add_filter( 'bupr_review_tab_name', function( $name ) {
    return 'Feedback';
});
```

#### `bupr_shortcode_show_stars`

Control whether stars appear in the shortcode output.

```php
apply_filters( 'bupr_shortcode_show_stars', $show, $user_id );
```

#### `bupr_widget_show_stars`

Control whether stars appear in widget output.

```php
apply_filters( 'bupr_widget_show_stars', $show );
```

#### `bupr_item_type_label`

Change the label for reviews (e.g., "reviews" → "testimonials").

```php
apply_filters( 'bupr_item_type_label', $label );
```

**Example: Change to testimonials**
```php
add_filter( 'bupr_item_type_label', function( $label ) {
    return 'testimonials';
});
```

### Settings Filters

#### `bupr_global_settings`

Modify the global settings array at runtime. Used by Pro addon.

```php
apply_filters( 'bupr_global_settings', $bupr );
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bupr` | array | All plugin settings |

**Example: Override a setting**
```php
add_filter( 'bupr_global_settings', function( $bupr ) {
    // Force 5 reviews per page
    $bupr['reviews_per_page'] = 5;
    return $bupr;
});
```

### Rating Filters

#### `bupr_aggregate_rating`

Modify the calculated aggregate rating before saving.

```php
apply_filters( 'bupr_aggregate_rating', $rating, $user_id, $review_ids, $count );
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rating` | float | Calculated average rating |
| `$user_id` | int | Member's user ID |
| `$review_ids` | array | Array of review post IDs |
| `$count` | int | Number of rated reviews |

**Example: Weighted rating by recency**
```php
add_filter( 'bupr_aggregate_rating', function( $rating, $user_id, $review_ids, $count ) {
    // Your custom calculation logic
    // Newer reviews could weigh more
    return $rating;
}, 10, 4 );
```

### Activity Filters

#### `bupr_allow_activity_posting`

Control whether reviews create activity stream items.

```php
apply_filters( 'bupr_allow_activity_posting', $allow );
```

**Example: Disable activity for anonymous reviews**
```php
add_filter( 'bupr_allow_activity_posting', function( $allow ) {
    // Check current review context
    global $current_review_data;
    if ( isset( $current_review_data['anonymous'] ) && $current_review_data['anonymous'] ) {
        return false;
    }
    return $allow;
});
```

### Shortcode Filters

#### `bupr_shortcode_output`

Modify the complete shortcode HTML output.

```php
apply_filters( 'bupr_shortcode_output', $output );
```

#### `bupr_shortcode_rating_text`

Modify the rating text in shortcode (e.g., "Rating: 4.5/5 (10 reviews)").

```php
apply_filters( 'bupr_shortcode_rating_text', $text, $user_id, $rating, $count );
```

#### `bupr_shortcode_member_limit`

Override the number of members shown.

```php
apply_filters( 'bupr_shortcode_member_limit', $limit );
```

#### `bupr_display_top_members_shortcode_atts`

Modify default shortcode attributes.

```php
apply_filters( 'bupr_display_top_members_shortcode_atts', $defaults, $attrs );
```

### Query Filters

#### `bupr_member_query_args`

Modify the user query for the review form member dropdown.

```php
apply_filters( 'bupr_member_query_args', $args );
```

**Example: Exclude specific users**
```php
add_filter( 'bupr_member_query_args', function( $args ) {
    $args['exclude'] = array_merge( $args['exclude'], [ 5, 10, 15 ] );
    return $args;
});
```

#### `bupr_member_list`

Filter the list of members available for review.

```php
apply_filters( 'bupr_member_list', $members );
```

## Integration Examples

### Add Custom Fields to Reviews

```php
// Add field to form
add_action( 'bupr_after_rating_fields', function() {
    ?>
    <div class="custom-review-field">
        <label for="project_type">Project Type</label>
        <input type="text" name="project_type" id="project_type">
    </div>
    <?php
});

// Save custom field
add_action( 'bupr_member_review_after_review_insert', function( $review_id, $member_id ) {
    if ( isset( $_POST['project_type'] ) ) {
        update_post_meta( $review_id, 'project_type', sanitize_text_field( $_POST['project_type'] ) );
    }
}, 10, 2 );
```

### Restrict Reviews Based on Custom Logic

```php
add_filter( 'bupr_show_profile_review_tab', function( $show, $user_id ) {
    // Only show reviews tab for users with complete profiles
    $profile_complete = get_user_meta( $user_id, 'profile_complete', true );
    return $profile_complete === 'yes';
}, 10, 2 );
```

### Custom Notification System

```php
add_action( 'bupr_member_review_after_review_insert', function( $review_id, $member_id ) {
    // Send push notification
    $member = get_userdata( $member_id );
    $review = get_post( $review_id );

    my_push_notification_function(
        $member->user_email,
        'New Review Received',
        'Someone just reviewed your profile!'
    );
}, 10, 2 );
```
