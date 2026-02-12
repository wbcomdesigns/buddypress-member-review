# Hooks & Filters

Complete reference of actions and filters available in BuddyPress Member Reviews.

## Actions

### `bupr_member_review_after_review_insert`

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

### `bupr_sent_review_notification`

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

### `bupr_before_member_review_list`

Fires before the review list on member profile.

**Example:**

```php
add_action( 'bupr_before_member_review_list', function() {
    echo '<div class="custom-notice">Member reviews are moderated.</div>';
} );
```

---

### `bupr_after_member_review_list`

Fires after the review list on member profile.

**Example:**

```php
add_action( 'bupr_after_member_review_list', function() {
    echo '<div class="review-disclaimer">Reviews reflect individual opinions.</div>';
} );
```

---

### `bupr_after_review_content`

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

### `bupr_after_review_date`

Fires after review date display. Used by Pro for verified badges.

**Example:**

```php
add_action( 'bupr_after_review_date', function() {
    global $bupr_review;
    if ( get_post_meta( $bupr_review->ID, 'custom_verified', true ) ) {
        echo '<span class="verified-badge">✓ Verified Purchase</span>';
    }
} );
```

---

## Filters

### `bupr_global_settings`

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

### `bupr_show_profile_review_tab`

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

### `bupr_review_tab_name`

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

### `bupr_shortcode_output`

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

### `bupr_allow_activity_posting`

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

### `bupr_review_form_fields`

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

### `bupr_review_template`

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

### `bupr_edit_review_extra_fields`

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
