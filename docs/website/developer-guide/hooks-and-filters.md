# Hooks & Filters

Complete reference of actions and filters available in BuddyPress Member Reviews.

## Actions

### Review Lifecycle

#### `bupr_before_save_review`

Fires before a review is saved to the database. Use this to validate or modify data before insertion.

**Location:** `includes/bupr-ajax.php`

**Example:**

```php
add_action( 'bupr_before_save_review', function() {
    // Perform custom validation before review is saved
    if ( ! current_user_can( 'publish_posts' ) ) {
        wp_send_json_error( 'You do not have permission to submit reviews.' );
    }
} );
```

---

#### `bupr_member_review_after_review_insert`

Fires after a new review is created.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$review_id` | int | The review post ID |
| `$member_id` | int | The reviewed member's user ID |

**Example:**

```php
add_action( 'bupr_member_review_after_review_insert', function( $review_id, $member_id ) {
    // Send custom notification
    $reviewer = wp_get_current_user();
    $member = get_userdata( $member_id );

    wp_mail(
        $member->user_email,
        'New Review Received',
        sprintf( '%s reviewed you.', $reviewer->display_name )
    );
}, 10, 2 );
```

---

#### `bupr_sent_review_notification`

Fires after review notification is sent.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$member_id` | int | The reviewed member's user ID |
| `$review_id` | int | The review post ID |

**Example:**

```php
add_action( 'bupr_sent_review_notification', function( $member_id, $review_id ) {
    // Log notification in custom system
    error_log( sprintf( 'Review notification sent: User %d, Review %d', $member_id, $review_id ) );
}, 10, 2 );
```

---

#### `bupr_owner_reply_added`

Fires after a profile owner submits a reply to a review.

**Location:** `includes/bupr-ajax.php`

**Example:**

```php
add_action( 'bupr_owner_reply_added', function() {
    // Notify the original reviewer that the profile owner replied
    error_log( 'Owner reply was added to a review.' );
} );
```

---

#### `bupr_review_flagged`

Fires after a review is flagged/reported by a user.

**Location:** `includes/bupr-ajax.php`

**Example:**

```php
add_action( 'bupr_review_flagged', function() {
    // Send alert to admin when a review is reported
    wp_mail(
        get_option( 'admin_email' ),
        'Review Flagged',
        'A review has been flagged for moderation.'
    );
} );
```

---

#### `bupr_recalculation_completed`

Fires after batch rating recalculation finishes for all members.

**Location:** `includes/bupr-general-functions.php`

**Example:**

```php
add_action( 'bupr_recalculation_completed', function() {
    // Clear custom caches after ratings are recalculated
    wp_cache_flush_group( 'bupr_ratings' );
    error_log( 'Rating recalculation completed.' );
} );
```

---

### Template Hooks

#### `bupr_before_member_review_list`

Fires before the review list on member profile.

**Example:**

```php
add_action( 'bupr_before_member_review_list', function() {
    echo '<div class="custom-notice">Member reviews are moderated.</div>';
} );
```

---

#### `bupr_after_member_review_list`

Fires after the review list on member profile.

**Example:**

```php
add_action( 'bupr_after_member_review_list', function() {
    echo '<div class="review-disclaimer">Reviews reflect individual opinions.</div>';
} );
```

---

#### `bupr_after_review_content`

Fires after individual review content. Used by Pro for skill tags.

**Example:**

```php
add_action( 'bupr_after_review_content', function() {
    global $bupr_review;
    $custom_data = get_post_meta( $bupr_review->ID, 'custom_field', true );
    if ( $custom_data ) {
        echo '<div class="custom-data">' . esc_html( $custom_data ) . '</div>';
    }
} );
```

---

#### `bupr_after_review_date`

Fires after review date display. Used by Pro for verified badges.

**Example:**

```php
add_action( 'bupr_after_review_date', function() {
    global $bupr_review;
    if ( get_post_meta( $bupr_review->ID, 'custom_verified', true ) ) {
        echo '<span class="verified-badge">Verified Purchase</span>';
    }
} );
```

---

#### `bupr_after_rating_fields`

Fires after rating star fields in the review form. Used by the Pro addon to add additional form elements.

**Location:** `includes/bupr-shortcodes.php`

**Example:**

```php
add_action( 'bupr_after_rating_fields', function() {
    echo '<div class="custom-field-group">';
    echo '<label for="custom_note">Additional Notes</label>';
    echo '<textarea name="custom_note" id="custom_note"></textarea>';
    echo '</div>';
} );
```

---

### GamiPress Integration

#### `gamipress_bp_member_review`

GamiPress integration trigger that fires when a review is submitted. Use this to award points or achievements through GamiPress.

**Location:** `includes/bupr-ajax.php`

**Example:**

```php
// This hook is consumed by GamiPress automatically when the
// BuddyPress Member Reviews integration is active.
// No manual setup needed if GamiPress is installed.
```

---

## Filters

### Settings & Configuration

#### `bupr_global_settings`

Modify plugin settings at runtime. Used by Pro for mode switching.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bupr` | array | Current plugin settings |

**Returns:** `array` Modified settings

**Example:**

```php
add_filter( 'bupr_global_settings', function( $bupr ) {
    // Disable anonymous reviews programmatically
    $bupr['bupr_allow_anonymous_reviews'] = 'no';

    // Change review criteria
    $bupr['bupr_multi_criteria_ratings'] = array(
        'quality' => 'Quality',
        'service' => 'Service'
    );

    return $bupr;
} );
```

---

### Profile Tab

#### `bupr_show_profile_review_tab`

Control whether reviews tab appears on profile.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$show` | bool | Whether to show tab |

**Returns:** `bool` Modified visibility

**Example:**

```php
add_filter( 'bupr_show_profile_review_tab', function( $show ) {
    // Hide reviews tab for specific member types
    $member_type = bp_get_member_type( bp_displayed_user_id() );
    if ( $member_type === 'vendor' ) {
        return false;
    }
    return $show;
} );
```

---

#### `bupr_review_tab_name`

Change the reviews tab label.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | string | Current tab name |

**Returns:** `string` Modified tab name

**Example:**

```php
add_filter( 'bupr_review_tab_name', function( $name ) {
    return 'Testimonials';
} );
```

---

#### `bupr_profile_review_singular_tab_name`

Change the singular form of the reviews tab name (used when the tab count is 1).

**Location:** `includes/bupr-general-functions.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | string | Singular tab name |

**Returns:** `string` Modified singular tab name

**Example:**

```php
add_filter( 'bupr_profile_review_singular_tab_name', function( $name ) {
    return 'Testimonial';
} );
```

---

#### `bupr_default_subnav_slug`

Change the default subnav slug for the reviews tab on BuddyPress profiles.

**Location:** `includes/bupr-general-functions.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$slug` | string | Default subnav slug |

**Returns:** `string` Modified subnav slug

**Example:**

```php
add_filter( 'bupr_default_subnav_slug', function( $slug ) {
    return 'my-custom-subnav';
} );
```

---

### Activity Stream

#### `bupr_allow_activity_posting`

Enable or disable activity stream posts for reviews.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$allow` | bool | Whether to allow activity posts |

**Returns:** `bool` Modified permission

**Example:**

```php
add_filter( 'bupr_allow_activity_posting', function( $allow ) {
    // Disable activity posts during business hours
    $hour = (int) date( 'H' );
    if ( $hour >= 9 && $hour <= 17 ) {
        return false;
    }
    return $allow;
} );
```

---

#### `bupr_member_review_activity_action`

Customize the activity stream action text displayed for review activities.

**Location:** `includes/bupr-filters.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$action` | string | Activity action text |

**Returns:** `string` Modified action text

**Example:**

```php
add_filter( 'bupr_member_review_activity_action', function( $action ) {
    // Replace default activity text with custom wording
    return str_replace( 'posted a review', 'shared feedback about', $action );
} );
```

---

### Anonymous Reviews

#### `bupr_change_user_link`

Modify the user link for anonymous reviews displayed in the activity stream.

**Location:** `includes/bupr-filters.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$link` | string | User profile link HTML |

**Returns:** `string` Modified link HTML

**Example:**

```php
add_filter( 'bupr_change_user_link', function( $link ) {
    return '<span class="anonymous-label">Anonymous Member</span>';
} );
```

---

#### `bupr_change_avatar_image`

Modify the avatar image for anonymous reviews in the activity stream.

**Location:** `includes/bupr-filters.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$avatar` | string | Avatar image HTML |

**Returns:** `string` Modified avatar HTML

**Example:**

```php
add_filter( 'bupr_change_avatar_image', function( $avatar ) {
    // Use a custom anonymous avatar image
    return '<img src="' . esc_url( get_stylesheet_directory_uri() . '/images/anon-avatar.png' ) . '" class="avatar" width="50" height="50" />';
} );
```

---

#### `bupr_default_avatar_size`

Change the avatar size used for anonymous review avatars. Default is 50.

**Location:** `includes/bupr-filters.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$size` | int | Avatar size in pixels (default: 50) |

**Returns:** `int` Modified avatar size

**Example:**

```php
add_filter( 'bupr_default_avatar_size', function( $size ) {
    return 80;
} );
```

---

#### `bupr_change_default_avatar`

Change the default avatar type used for anonymous reviews. Default is `'mystery'`.

**Location:** `includes/bupr-filters.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | string | Avatar type (default: 'mystery') |

**Returns:** `string` Modified avatar type

**Example:**

```php
add_filter( 'bupr_change_default_avatar', function( $type ) {
    return 'blank'; // Use a blank avatar instead of mystery person
} );
```

---

### Review Display

#### `bupr_aggregate_rating`

Modify the calculated aggregate (average) rating for a member. Useful for weighting or adjusting ratings.

**Location:** `includes/bupr-general-functions.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rating` | float | Calculated aggregate rating |

**Returns:** `float` Modified aggregate rating

**Example:**

```php
add_filter( 'bupr_aggregate_rating', function( $rating ) {
    // Round to nearest half star
    return round( $rating * 2 ) / 2;
} );
```

---

#### `bupr_reviewer_name_html`

Modify the reviewer name HTML displayed in the review list.

**Location:** `includes/templates/bupr-reviews-tab-template.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html` | string | Reviewer name HTML |

**Returns:** `string` Modified HTML

**Example:**

```php
add_filter( 'bupr_reviewer_name_html', function( $html ) {
    // Add a custom badge next to reviewer names
    return $html . ' <span class="reviewer-badge">Member</span>';
} );
```

---

### Shortcode & Widget

#### `bupr_shortcode_output`

Modify shortcode output HTML.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html` | string | Shortcode output HTML |
| `$atts` | array | Shortcode attributes |

**Returns:** `string` Modified HTML

**Example:**

```php
add_filter( 'bupr_shortcode_output', function( $html, $atts ) {
    // Wrap shortcode output in custom container
    return '<div class="custom-reviews-wrapper">' . $html . '</div>';
}, 10, 2 );
```

---

#### `bupr_display_top_members_shortcode_atts`

Modify the default shortcode attributes for `[bupr_display_top_members]`.

**Location:** `includes/bupr-shortcodes.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$atts` | array | Shortcode default attributes |

**Returns:** `array` Modified attributes

**Example:**

```php
add_filter( 'bupr_display_top_members_shortcode_atts', function( $atts ) {
    // Change default number of members shown
    $atts['count'] = 10;
    return $atts;
} );
```

---

#### `bupr_shortcode_show_stars`

Control whether star ratings are displayed in shortcode output.

**Location:** `includes/bupr-shortcodes.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$show` | bool | Whether to show stars |

**Returns:** `bool` Modified visibility

**Example:**

```php
add_filter( 'bupr_shortcode_show_stars', function( $show ) {
    // Hide stars on specific pages
    if ( is_page( 'testimonials' ) ) {
        return false;
    }
    return $show;
} );
```

---

#### `bupr_item_type_label`

Change the "reviews" label text used in shortcode and widget output.

**Location:** `includes/bupr-shortcodes.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$label` | string | Label text (default: 'reviews') |

**Returns:** `string` Modified label

**Example:**

```php
add_filter( 'bupr_item_type_label', function( $label ) {
    return 'testimonials';
} );
```

---

#### `bupr_widget_show_stars`

Control whether star ratings are displayed in widget output (both Member Rating and Display Review widgets).

**Location:** `admin/widget/member-rating.php`, `admin/widget/display-review.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$show` | bool | Whether to show stars |

**Returns:** `bool` Modified visibility

**Example:**

```php
add_filter( 'bupr_widget_show_stars', function( $show ) {
    return false; // Hide stars in all review widgets
} );
```

---

#### `bupr_member_query_args`

Modify the WP_User_Query arguments used to populate the member dropdown in the review form.

**Location:** `includes/bupr-shortcodes.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$args` | array | WP_User_Query arguments |

**Returns:** `array` Modified query arguments

**Example:**

```php
add_filter( 'bupr_member_query_args', function( $args ) {
    // Only show members with a specific role in the dropdown
    $args['role'] = 'vendor';
    return $args;
} );
```

---

#### `bupr_member_list`

Modify the member list array used for the review form dropdown after the query runs.

**Location:** `includes/bupr-shortcodes.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$members` | array | Array of user objects |

**Returns:** `array` Modified member list

**Example:**

```php
add_filter( 'bupr_member_list', function( $members ) {
    // Remove current user from the dropdown
    return array_filter( $members, function( $member ) {
        return $member->ID !== get_current_user_id();
    } );
} );
```

---

### Asset Loading

#### `bupr_should_load_assets`

Control when plugin CSS and JS assets are loaded on the frontend. Return `false` to prevent loading on the current page.

**Location:** `includes/bupr-scripts.php`

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$load` | bool | Whether to load assets (default: true) |

**Returns:** `bool` Modified loading decision

**Example:**

```php
add_filter( 'bupr_should_load_assets', function( $load ) {
    // Only load assets on BuddyPress pages and pages with review shortcodes
    if ( ! is_buddypress() && ! has_shortcode( get_post()->post_content ?? '', 'bupr_display_top_members' ) ) {
        return false;
    }
    return $load;
} );
```

---

### Form & Edit

#### `bupr_review_form_fields`

> **Note:** This is a Pro-only hook (added by the Pro addon).



Modify review form fields.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fields` | array | Form fields configuration |

**Returns:** `array` Modified fields

**Example:**

```php
add_filter( 'bupr_review_form_fields', function( $fields ) {
    // Add custom field
    $fields['custom_field'] = array(
        'label'    => 'Project Type',
        'type'     => 'select',
        'options'  => array(
            'web' => 'Web Development',
            'app' => 'App Development'
        ),
        'required' => true
    );
    return $fields;
} );
```

---

#### `bupr_review_template`

> **Note:** This is a Pro-only hook (added by the Pro addon).

Use custom review template file.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$template` | string | Template file path |

**Returns:** `string` Modified template path

**Example:**

```php
add_filter( 'bupr_review_template', function( $template ) {
    // Use custom template for specific member types
    $member_type = bp_get_member_type( bp_displayed_user_id() );
    if ( $member_type === 'vendor' ) {
        return get_stylesheet_directory() . '/vendor-reviews.php';
    }
    return $template;
} );
```

---

#### `bupr_edit_review_extra_fields`

Add custom fields to edit review form.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html` | string | Current extra fields HTML |
| `$review_id` | int | Review post ID |

**Returns:** `string` Modified HTML

**Example:**

```php
add_filter( 'bupr_edit_review_extra_fields', function( $html, $review_id ) {
    $custom_value = get_post_meta( $review_id, 'custom_field', true );

    $html .= '<div class="form-group">';
    $html .= '<label>Custom Field</label>';
    $html .= '<input type="text" name="custom_field" value="' . esc_attr( $custom_value ) . '">';
    $html .= '</div>';

    return $html;
}, 10, 2 );
```

---

## Hook Priority

All plugin hooks use default priority (10). To execute your code before plugin logic, use priority less than 10. To execute after, use priority greater than 10.

**Example:**

```php
// Run before plugin
add_filter( 'bupr_global_settings', 'my_function', 5 );

// Run after plugin
add_action( 'bupr_member_review_after_review_insert', 'my_function', 15 );
```

---

## Related Documentation

- [Template Overrides](templates.md)
- [Database Schema](database.md)
- [REST API Reference](rest-api.md)
