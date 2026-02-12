# Template Overrides

BuddyPress Member Reviews uses a template hierarchy system that allows you to override plugin templates in your theme without modifying plugin files.

## Template Hierarchy

The plugin searches for templates in this order:

1. `your-theme/buddypress-member-review/template-name.php` (your override)
2. `your-theme/template-name.php` (alternative location)
3. `plugin/includes/templates/template-name.php` (default)

This ensures your customizations are preserved during plugin updates.

---

## Available Templates

### Main Templates

| Template | Purpose |
|----------|---------|
| `bupr-reviews-tab-template.php` | Reviews tab content on member profile |
| `bupr-single-review-template.php` | Individual review display |
| `bupr-edit-review-form.php` | Edit review form |
| `bupr-review-form.php` | New review submission form |
| `bupr-review-list.php` | Review list wrapper |

### Partial Templates

| Template | Purpose |
|----------|---------|
| `bupr-rating-stars.php` | Star rating display |
| `bupr-aggregate-rating.php` | Aggregate rating summary |
| `bupr-review-actions.php` | Review action buttons (edit, delete, reply) |

---

## Overriding Templates

### Step 1: Create Directory

Create the template directory in your theme:

```bash
your-theme/
└── buddypress-member-review/
```

Or use the root theme directory:

```bash
your-theme/
```

### Step 2: Copy Template

Copy the template from the plugin to your theme:

```bash
wp-content/plugins/buddypress-member-review/includes/templates/bupr-reviews-tab-template.php
```

To:

```bash
wp-content/themes/your-theme/buddypress-member-review/bupr-reviews-tab-template.php
```

### Step 3: Customize

Edit the copied template. Your changes will be used instead of the plugin default.

### Step 4: Test

Clear any caching plugins and visit a member profile to see your changes.

---

## Template Variables

Templates have access to these global variables and functions:

### Global Variables

```php
global $bupr_review;        // Current review post object
global $bupr_member_id;     // Reviewed member ID
global $bupr_reviewer_id;   // Reviewer user ID
global $bupr;               // Plugin settings array
```

### Template Functions

| Function | Description |
|----------|-------------|
| `bupr_get_review_rating( $review_id )` | Get review rating array |
| `bupr_get_aggregate_rating( $user_id )` | Get user's aggregate rating |
| `bupr_can_user_review( $user_id, $member_id )` | Check if user can review member |
| `bupr_user_has_reviewed( $user_id, $member_id )` | Check if user already reviewed |
| `bupr_get_review_count( $user_id )` | Get member's review count |
| `bupr_get_rating_criteria()` | Get rating criteria array |
| `bupr_render_stars( $rating, $criteria )` | Output star HTML |

---

## Example: Custom Review Template

Override `bupr-single-review-template.php`:

```php
<?php
/**
 * Single review template override
 */

global $bupr_review;

$reviewer_id = $bupr_review->post_author;
$reviewer    = get_userdata( $reviewer_id );
$is_anonymous = get_post_meta( $bupr_review->ID, 'bupr_anonymous_review_post', true );
$rating      = get_post_meta( $bupr_review->ID, 'profile_star_rating', true );
?>

<div class="custom-review-item" id="review-<?php echo esc_attr( $bupr_review->ID ); ?>">

    <div class="review-header">
        <?php if ( ! $is_anonymous ) : ?>
            <div class="reviewer-avatar">
                <?php echo get_avatar( $reviewer_id, 60 ); ?>
            </div>
            <div class="reviewer-info">
                <h4 class="reviewer-name">
                    <a href="<?php echo bp_core_get_user_domain( $reviewer_id ); ?>">
                        <?php echo esc_html( $reviewer->display_name ); ?>
                    </a>
                </h4>
                <span class="review-date">
                    <?php echo human_time_diff( strtotime( $bupr_review->post_date ), current_time( 'timestamp' ) ); ?> ago
                </span>
            </div>
        <?php else : ?>
            <div class="reviewer-info">
                <h4 class="reviewer-name">Anonymous Reviewer</h4>
                <span class="review-date">
                    <?php echo human_time_diff( strtotime( $bupr_review->post_date ), current_time( 'timestamp' ) ); ?> ago
                </span>
            </div>
        <?php endif; ?>
    </div>

    <div class="review-rating">
        <?php
        if ( is_array( $rating ) ) {
            foreach ( $rating as $criteria => $value ) {
                echo '<div class="criteria-rating">';
                echo '<span class="criteria-label">' . esc_html( ucfirst( $criteria ) ) . ':</span>';
                echo bupr_render_stars( $value, $criteria );
                echo '</div>';
            }
        }
        ?>
    </div>

    <div class="review-content">
        <?php echo wpautop( wp_kses_post( $bupr_review->post_content ) ); ?>
    </div>

    <?php
    // Hook for additional content (used by Pro for skill tags)
    do_action( 'bupr_after_review_content' );
    ?>

    <div class="review-actions">
        <?php bupr_review_action_buttons( $bupr_review->ID ); ?>
    </div>

    <?php
    // Display owner reply if exists
    $owner_reply = get_post_meta( $bupr_review->ID, 'bupr_owner_reply', true );
    if ( $owner_reply ) :
        $reply_date = get_post_meta( $bupr_review->ID, 'bupr_owner_reply_date', true );
        ?>
        <div class="owner-reply">
            <strong>Owner Response:</strong>
            <p><?php echo wpautop( wp_kses_post( $owner_reply ) ); ?></p>
            <?php if ( $reply_date ) : ?>
                <span class="reply-date">
                    <?php echo human_time_diff( $reply_date, current_time( 'timestamp' ) ); ?> ago
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
```

---

## CSS Classes Reference

Use these classes to style review elements:

### Container Classes

```css
.bupr-reviews-wrapper          /* Main wrapper */
.bupr-review-item              /* Single review */
.bupr-review-list              /* Review list container */
.bupr-review-form              /* Review form */
.bupr-aggregate-rating         /* Aggregate rating display */
```

### Component Classes

```css
.bupr-reviewer-avatar          /* Reviewer avatar */
.bupr-reviewer-name            /* Reviewer name */
.bupr-review-date              /* Review date */
.bupr-review-content           /* Review text content */
.bupr-star-rating              /* Star rating container */
.bupr-star-filled              /* Filled star */
.bupr-star-empty               /* Empty star */
.bupr-criteria-label           /* Rating criteria label */
.bupr-owner-reply              /* Owner's reply */
```

### State Classes

```css
.bupr-review-pending           /* Pending review */
.bupr-review-anonymous         /* Anonymous review */
.bupr-review-flagged           /* Flagged review */
.bupr-review-verified          /* Verified review (Pro) */
```

### Action Classes

```css
.bupr-edit-review              /* Edit button */
.bupr-delete-review            /* Delete button */
.bupr-reply-review             /* Reply button */
.bupr-flag-review              /* Flag button */
```

---

## Template Hooks

Add custom content to templates without overriding:

```php
// Before review list
add_action( 'bupr_before_member_review_list', function() {
    echo '<div class="custom-notice">Custom content here</div>';
} );

// After review content
add_action( 'bupr_after_review_content', function() {
    global $bupr_review;
    // Add custom fields display
} );

// After review date
add_action( 'bupr_after_review_date', function() {
    // Add custom badges
} );
```

---

## Best Practices

1. **Never modify plugin files directly** - Always use theme overrides
2. **Test after plugin updates** - Verify your customizations still work
3. **Use child themes** - Protect overrides from theme updates
4. **Keep it semantic** - Use appropriate HTML5 elements
5. **Check for Pro features** - Use `function_exists()` for Pro-specific functions
6. **Escape output** - Use `esc_html()`, `esc_attr()`, `wp_kses_post()`
7. **Cache-friendly** - Avoid expensive queries in templates

---

## Debugging Templates

Enable WordPress debug mode to see which template is being used:

```php
// Add to wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

Add debug output to your template:

```php
// At top of template file
error_log( 'Using template: ' . __FILE__ );
```

---

## Related Documentation

- [Hooks & Filters](hooks-and-filters.md)
- [CSS Customization](../customization/styling.md)
- [Database Schema](database.md)
