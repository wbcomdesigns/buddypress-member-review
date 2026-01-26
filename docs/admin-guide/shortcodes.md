# Shortcodes

Display top-rated or most-reviewed members anywhere on your site using shortcodes.

> **Video Guide:** Watch the [Shortcodes & Widgets Guide (30s)](https://wbcomdesigns.com/videos/bp-member-reviews/shortcodes-guide) for a quick visual walkthrough.

## Top Members Shortcode

```
[bupr_display_top_members]
```

Displays a list of members sorted by rating or review count.

### Parameters

| Parameter | Values | Default | Description |
|-----------|--------|---------|-------------|
| `title` | Any text | (none) | Heading above the list |
| `total_member` | Number | 5 | How many members to show |
| `type` | `top rated` or `top reviewed` | `top rated` | Sort by rating or review count |
| `avatar` | `show` or `hide` | `show` | Display member avatars |

### Examples

**Basic usage:**
```
[bupr_display_top_members]
```

**With title and limit:**
```
[bupr_display_top_members title="Our Top Rated Members" total_member="10"]
```

**Most reviewed members:**
```
[bupr_display_top_members title="Most Reviewed" type="top reviewed" total_member="5"]
```

**Without avatars:**
```
[bupr_display_top_members avatar="hide"]
```

**Full example:**
```
[bupr_display_top_members title="Community Stars" total_member="8" type="top rated" avatar="show"]
```

### Display Details

Each member entry shows:

- Avatar (if enabled)
- Display name (linked to profile)
- Star rating visualization
- Rating score (e.g., "4.5/5")
- Review count

## Where to Use

### Pages
Add the shortcode to any page content using the block editor or classic editor.

### Widgets
Use a "Custom HTML" or "Shortcode" widget in any widget area.

### Theme Templates
```php
<?php echo do_shortcode('[bupr_display_top_members total_member="5"]'); ?>
```

### Page Builders
Most page builders (Elementor, Beaver Builder, Divi) support shortcodes directly.

## Styling

The shortcode outputs these CSS classes for customization:

```css
/* Main container */
.bupr-shortcode-top-members-contents { }

/* List container */
.bupr-member-main { }

/* Individual member */
.bupr-members { }

/* Avatar container */
.bupr-img-widget { }

/* Content container */
.bupr-content-widget { }

/* Member name */
.bupr-member-title { }

/* Star rating */
.bupr-member-rating { }

/* Rating text */
.bupr-meta { }
```

### Example Custom CSS

```css
/* Horizontal layout */
.bupr-member-main {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.bupr-members {
    flex: 1 1 200px;
    text-align: center;
}

/* Larger avatars */
.bupr-img-widget img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
}

/* Custom star color */
.bupr-star-rate {
    color: #e74c3c;
}
```

## Pro Mode Compatibility

When using BuddyPress Member Reviews Pro in different modes:

| Mode | Shortcode Behavior |
|------|-------------------|
| Ratings | Shows star ratings and score |
| Testimonials | Shows review count only (no stars) |
| Endorsements | Shows endorsement count only |

The shortcode automatically adapts using Pro's filters.

## Performance Notes

- The shortcode runs an optimized SQL query
- Results are not cached by default
- For high-traffic sites, consider caching the page or using a caching plugin

## Troubleshooting

### No members displayed

- Ensure at least one member has received a published review
- Check that the reviewed members have the correct user role

### Wrong members showing

- Verify the `type` parameter matches your intent
- Remember: "top rated" = highest rating, "top reviewed" = most reviews

### Styling not applied

- Check for CSS conflicts with your theme
- Use browser inspector to verify class names
- Add `!important` to override stubborn theme styles
