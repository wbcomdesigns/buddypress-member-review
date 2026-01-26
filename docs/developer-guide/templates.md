# Template Overrides

Customize how reviews are displayed by overriding the plugin's templates in your theme.

## How Template Overrides Work

The plugin looks for templates in this order:

1. `your-theme/review/template-name.php` (your override)
2. `your-theme/buddypress/review/template-name.php` (alternative location)
3. `plugin/includes/templates/template-name.php` (default)

## Available Templates

| Template | Purpose |
|----------|---------|
| `bupr-reviews-tab-template.php` | Main reviews list on profile |
| `bupr-single-review-template.php` | Individual review display |
| `bupr-edit-review-form.php` | Review edit form (lightbox) |

## Creating Template Overrides

### Step 1: Create the Directory

Create a `review` folder in your theme:

```
your-theme/
└── review/
    └── (templates go here)
```

### Step 2: Copy the Template

Copy the template you want to modify from:
```
wp-content/plugins/buddypress-member-review/includes/templates/
```

To:
```
wp-content/themes/your-theme/review/
```

### Step 3: Customize

Edit the copied template. Your changes will override the plugin's default.

## Template: bupr-reviews-tab-template.php

This template displays the list of reviews on a member's profile tab.

### Key Variables

| Variable | Type | Description |
|----------|------|-------------|
| `$reviews_loop` | WP_Query | Reviews query object |
| `$review_edit_allowed` | bool | Whether editing is enabled |
| `$member_id` | int | Profile owner's user ID |
| `$current_user_id` | int | Logged-in user's ID |
| `$bupr` | array | Global settings array |

### Structure Overview

```php
<?php if ( $reviews_loop->have_posts() ) : ?>

    <?php while ( $reviews_loop->have_posts() ) : $reviews_loop->the_post(); ?>

        <!-- Single review card -->
        <div class="bupr-single-review">
            <!-- Avatar -->
            <!-- Reviewer name -->
            <!-- Date -->
            <!-- Star ratings -->
            <!-- Review content -->
            <!-- Owner reply -->
            <!-- Actions (edit, flag) -->
        </div>

    <?php endwhile; ?>

    <!-- Pagination -->

<?php else : ?>

    <!-- No reviews message -->

<?php endif; ?>
```

### Customization Examples

**Add custom badge next to reviewer name:**

Find the reviewer name section and add:

```php
<span class="reviewer-name">
    <?php echo esc_html( $reviewer_name ); ?>
    <?php if ( user_can( $author_id, 'premium_member' ) ) : ?>
        <span class="premium-badge">Premium</span>
    <?php endif; ?>
</span>
```

**Change date format:**

Find the date output and modify:

```php
// Default
echo esc_html( get_the_date() );

// Custom format
echo esc_html( get_the_date( 'F j, Y' ) );

// Relative time
echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) . ' ago';
```

## Template: bupr-single-review-template.php

Used when displaying a single review (not commonly overridden).

### Key Variables

Same as reviews-tab-template, but for a single review context.

## Template: bupr-edit-review-form.php

The lightbox form for editing reviews.

### Key Variables

| Variable | Type | Description |
|----------|------|-------------|
| `$review` | WP_Post | The review being edited |
| `$ratings` | array | Current rating values |
| `$bupr` | array | Global settings |

### Form Fields

- Subject input
- Rating stars (per criterion)
- Review content textarea
- Submit button

## CSS Classes Reference

### Review Container

```css
.bupr-reviews-container { }
.bupr-review-item { }
.bupr-single-review { }
```

### Review Header

```css
.review-header-left { }
.bp-user-review-avatar { }
.user_review_name { }
.user-review-date { }
```

### Ratings

```css
.rating-sec { }
.bupr-criteria-label { }
.bupr-star-rate { }
.rating-num { }
```

### Content

```css
.bp_review_content { }
.bp-reviews-user-content { }
```

### Actions

```css
.bupr-review-actions { }
.bupr-edit-review { }
.bupr-flag-review { }
```

### Owner Reply

```css
.bupr-owner-reply { }
.bupr-reply-header { }
.bupr-reply-content { }
.bupr-reply-form { }
```

### Flagging

```css
.bupr-flag-btn { }
.bupr-flag-modal { }
.bupr-flag-reasons { }
```

## JavaScript Integration

If your template changes require JavaScript modifications, enqueue your script after the plugin's:

```php
add_action( 'wp_enqueue_scripts', function() {
    if ( bp_is_user() ) {
        wp_enqueue_script(
            'my-review-customizations',
            get_stylesheet_directory_uri() . '/js/review-custom.js',
            array( 'bupr-front' ), // Depend on plugin's script
            '1.0.0',
            true
        );
    }
});
```

## Common Customizations

### Hide Star Rating Numbers

```css
.rating-num {
    display: none;
}
```

### Change Review Card Layout

Override the template and restructure:

```php
<div class="bupr-single-review custom-layout">
    <div class="review-sidebar">
        <!-- Avatar, name, date -->
    </div>
    <div class="review-main">
        <!-- Ratings, content -->
    </div>
</div>
```

### Add Share Buttons

In your template override:

```php
<div class="review-share">
    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title() ); ?>">
        Share on Twitter
    </a>
</div>
```

### Custom Empty State

Replace the "no reviews" message:

```php
<?php else : ?>
    <div class="no-reviews-custom">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/no-reviews.svg" alt="">
        <h3>No Reviews Yet</h3>
        <p>Be the first to share your experience!</p>
    </div>
<?php endif; ?>
```

## Updating Templates

When updating the plugin, check if your overridden templates need updates:

1. Compare plugin changelog for template changes
2. Diff your override against the new plugin version
3. Merge any structural changes while keeping your customizations

**Tip:** Add comments in your overrides marking your customizations:

```php
<!-- CUSTOM: Added premium badge -->
<span class="premium-badge">Premium</span>
<!-- END CUSTOM -->
```
