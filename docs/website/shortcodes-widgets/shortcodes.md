# Shortcodes

Use shortcodes to display member reviews anywhere on your WordPress site.

## Top Members Shortcode

Display your top-rated or most-reviewed members using the `[bupr_display_top_members]` shortcode.

### Basic Usage

```
[bupr_display_top_members]
```

This displays the top 5 members with their ratings using default settings.

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | string | `""` | Heading displayed above the member list |
| `total_member` | number | 5 | Number of members to show |
| `type` | string | `"top rated"` | Sort type: `top rated` or `most reviewed` |
| `avatar` | string | `"show"` | Show member avatars: `show` or `hide` |

### Examples

**Display top 10 rated members:**

```
[bupr_display_top_members total_member="10" type="top rated"]
```

**Show most reviewed members without avatars:**

```
[bupr_display_top_members title="Most Reviewed" total_member="8" type="most reviewed" avatar="hide"]
```

**Custom title with specific count:**

```
[bupr_display_top_members title="Community Leaders" total_member="3"]
```

### Where to Use

**Pages and Posts**

Add the shortcode directly in the WordPress editor (Block Editor or Classic Editor). The shortcode block works in both.

**Widget Areas**

Use the Shortcode widget to add it to sidebars, footers, or any widget area. Simply paste the shortcode into the widget.

**Template Files**

Add to theme templates using the `do_shortcode()` function:

```php
<?php echo do_shortcode('[bupr_display_top_members total_member="5"]'); ?>
```

**Page Builders**

Works with popular page builders:
- **Elementor**: Use the Shortcode widget
- **Beaver Builder**: Use the HTML module
- **WPBakery**: Use the Raw HTML element
- **Gutenberg**: Use the Shortcode block

### Pro Mode Compatibility

The shortcode automatically adapts to your Pro mode settings:

- **Review Mode**: Displays "Top Members" with review counts
- **Testimonial Mode**: Labels adapt to show testimonial counts
- **Endorsement Mode**: Shows endorsement counts and labels

The functionality remains the same—only the terminology changes to match your selected mode.

### Styling

The shortcode generates these CSS classes for custom styling:

```css
.bupr-shortcode-top-members-contents { } /* Outer wrapper div */
.bupr_members_review_setting { }         /* Additional wrapper class */
.bupr-member-main { }                    /* The <ul> list */
.bupr-members { }                        /* Each <li> item */
.bupr-img-widget { }                     /* Avatar wrapper */
.bupr-content-widget { }                 /* Content wrapper */
.bupr-member-title { }                   /* Member name link */
.bupr-member-rating { }                  /* Star rating area */
.bupr-meta { }                           /* Rating text ("X/5 (Y reviews)") */
```

Add custom CSS in **Appearance > Customize > Additional CSS** or your theme's stylesheet.

## Member Skills Shortcode (Pro)

Display a member's skills with ratings using the `[bupr_member_skills]` shortcode. This shortcode is available in the Pro version.

### Basic Usage

```
[bupr_member_skills user_id="123"]
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `user_id` | number | Displayed profile user | WordPress user ID of the member (uses `bp_displayed_user_id()`; returns 0 on non-BuddyPress pages) |
| `limit` | number | 10 | Maximum number of skills to display |

### Examples

**Display skills for specific member:**

```
[bupr_member_skills user_id="45" limit="5"]
```

**Show all skills (up to 50):**

```
[bupr_member_skills user_id="123" limit="50"]
```

**Display on member profile (uses current member):**

```
[bupr_member_skills]
```

### Where to Use

Use this shortcode on:
- Member profile pages
- Custom member directory pages
- Team showcase pages
- Staff listing pages

### Styling

Target these CSS classes for custom styling:

```css
.bupr-member-skills-wrap { }      /* Main container */
.bupr-skill-item { }              /* Individual skill */
.bupr-skill-name { }              /* Skill name */
.bupr-skill-rating { }            /* Skill rating */
```

## Featured Testimonials Shortcode (Pro)

Display featured testimonials in a responsive grid using the `[bupr_featured_testimonials]` shortcode. This shortcode is available in the Pro version and requires Testimonials mode to be active.

### Basic Usage

```
[bupr_featured_testimonials]
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `count` | number | 5 | Number of featured testimonials |
| `columns` | number | 3 | Grid columns (2, 3, or 4) |
| `orderby` | string | `"date"` | Sort field (date, title, etc.) |
| `order` | string | `"DESC"` | Sort order: `ASC` or `DESC` |

### How It Works

Only reviews that have been marked with the "Featured" flag by an admin will appear. This gives you full control over which testimonials are showcased on your site.

### Examples

**Display 6 featured testimonials in 2 columns:**

```
[bupr_featured_testimonials count="6" columns="2"]
```

**Show latest 3 featured testimonials in a 3-column grid:**

```
[bupr_featured_testimonials count="3" columns="3" order="DESC"]
```

**Oldest featured testimonials first:**

```
[bupr_featured_testimonials orderby="date" order="ASC"]
```

## Troubleshooting

**Shortcode displays as text**

Make sure your theme or page builder supports shortcodes. Try switching to a default WordPress theme to test.

**Members not showing**

Verify that:
- Members have received reviews
- Reviews are approved (if auto-approve is disabled)
- The member count parameter is not too high

**Styling looks broken**

Check for CSS conflicts with your theme. Use browser developer tools to inspect the generated HTML and override styles as needed.

## Related Documentation

- [Widgets](widgets.md) - Display reviews using WordPress widgets
- [Admin Settings Reference](../settings/admin-settings.md) - Configure review display options
- [Pro Features Overview](../settings/pro-features.md) - Learn about Pro shortcodes
