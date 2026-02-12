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
| `title` | string | "Top Members" | Heading displayed above the member list |
| `total_member` | number | 5 | Number of members to display (1-50) |
| `type` | string | "top_rated" | Sort type: `top_rated` or `most_reviewed` |
| `avatar` | string | "yes" | Show member avatars: `yes` or `no` |

### Examples

**Display top 10 rated members:**

```
[bupr_display_top_members total_member="10" type="top_rated"]
```

**Show most reviewed members without avatars:**

```
[bupr_display_top_members title="Most Reviewed" total_member="8" type="most_reviewed" avatar="no"]
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
.bupr-top-members-wrap { }        /* Main container */
.bupr-top-members-title { }       /* Title heading */
.bupr-top-member-item { }         /* Individual member item */
.bupr-member-avatar { }           /* Avatar wrapper */
.bupr-member-details { }          /* Name and rating container */
.bupr-member-rating { }           /* Rating display */
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
| `user_id` | number | Current user | WordPress user ID of the member |
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
