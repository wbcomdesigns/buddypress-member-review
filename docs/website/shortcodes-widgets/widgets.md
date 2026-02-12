# Widgets

Add member reviews to your sidebars, footers, and widget areas using WordPress widgets.

## BP Member Review Widget

Display your top-rated or most-reviewed members in any widget area.

### Widget Settings

| Setting | Options | Description |
|---------|---------|-------------|
| **Title** | Text field | Widget heading (appears above member list) |
| **Display** | Dropdown | `Top Rated` or `Most Reviewed` |
| **Number of Members** | Number | How many members to show (1-20) |
| **Show Avatar** | Checkbox | Display member profile pictures |

### Configuration Steps

1. Go to **Appearance > Widgets** (or use the Customizer)
2. Locate **BP Member Review** in the available widgets list
3. Drag the widget to your desired widget area (sidebar, footer, etc.)
4. Configure the settings:
   - Enter a title (e.g., "Top Community Members")
   - Select display type (Top Rated or Most Reviewed)
   - Set the number of members to display
   - Check "Show Avatar" if you want profile pictures
5. Click **Save**

### Example Configurations

**Sidebar - Top 5 Members with Avatars:**
- Title: "Community Stars"
- Display: Top Rated
- Number: 5
- Show Avatar: Checked

**Footer - Most Reviewed Members:**
- Title: "Most Popular Members"
- Display: Most Reviewed
- Number: 3
- Show Avatar: Unchecked

### Where to Use

**Standard Widget Areas:**
- Primary sidebar
- Secondary sidebar
- Footer columns
- Header widget areas

**BuddyPress-Specific Areas:**
- BuddyPress profile sidebar
- Activity directory sidebar
- Members directory sidebar
- Groups directory sidebar

**Theme-Specific Areas:**
- BuddyBoss sidebars
- Reign theme widget zones
- Custom theme widget areas

## BP Displayed Member Rating Widget

Show reviews for the currently displayed member profile. This widget is contextual and only appears on member profile pages.

### Widget Settings

| Setting | Options | Description |
|---------|---------|-------------|
| **Title** | Text field | Widget heading with `[user-name]` placeholder support |
| **Number of Reviews** | Number | How many reviews to display (1-20) |
| **Sort By** | Dropdown | `Newest`, `Oldest`, `Highest Rated`, or `Lowest Rated` |

### Configuration Steps

1. Go to **Appearance > Widgets**
2. Find **BP Displayed Member Rating** in the widget list
3. Add to a widget area that appears on member profiles
4. Configure settings:
   - Enter title with optional `[user-name]` placeholder
   - Set number of reviews to display
   - Choose sorting method
5. Click **Save**

### Using the [user-name] Placeholder

The `[user-name]` placeholder dynamically inserts the displayed member's name:

**Examples:**
- `Reviews for [user-name]` → "Reviews for John Doe"
- `[user-name]'s Ratings` → "John Doe's Ratings"
- `What people say about [user-name]` → "What people say about John Doe"

### Example Configurations

**Profile Sidebar - Recent Reviews:**
- Title: "Reviews for [user-name]"
- Number: 5
- Sort By: Newest

**Profile Page - Top Reviews:**
- Title: "[user-name]'s Best Reviews"
- Number: 3
- Sort By: Highest Rated

### Where to Use

This widget works best in:
- BuddyPress profile sidebar
- Member single page widget areas
- Profile-specific widget zones
- BuddyBoss profile areas

The widget automatically hides when not viewing a member profile.

## Theme Compatibility

### BuddyBoss Theme

Both widgets work seamlessly with BuddyBoss:
- Sidebar widgets appear in profile sidebars
- Footer widgets span full width
- Styling matches BuddyBoss design
- Mobile responsive by default

### Reign Theme

Optimized for Reign theme:
- Integrates with Reign widget areas
- Matches Reign's BuddyPress styling
- Works in custom Reign sidebars
- Responsive across all breakpoints

### Other Themes

Works with any WordPress theme that supports:
- Standard WordPress widget areas
- BuddyPress compatibility
- Widget sidebar functionality

## Styling Widgets

### CSS Classes

Target these classes for custom styling:

**BP Member Review Widget:**
```css
.bupr-member-review-widget { }       /* Main widget container */
.bupr-widget-member-item { }         /* Individual member item */
.bupr-widget-avatar { }              /* Avatar image */
.bupr-widget-member-name { }         /* Member name link */
.bupr-widget-rating { }              /* Rating display */
```

**BP Displayed Member Rating Widget:**
```css
.bupr-displayed-member-widget { }    /* Main widget container */
.bupr-widget-review-item { }         /* Individual review */
.bupr-widget-reviewer { }            /* Reviewer name */
.bupr-widget-review-rating { }       /* Review rating */
.bupr-widget-review-content { }      /* Review text */
```

### Custom CSS

Add custom styles in:
- **Appearance > Customize > Additional CSS**
- Your child theme's `style.css`
- A custom CSS plugin

## Pro Mode Compatibility

Both widgets automatically adapt to Pro mode settings:

**Review Mode (Default):**
- Shows "reviews" terminology
- Displays standard review labels
- Review counts and ratings

**Testimonial Mode:**
- Labels change to "testimonials"
- "Most Reviewed" becomes "Most Testimonials"
- Adapted terminology throughout

**Endorsement Mode:**
- Labels change to "endorsements"
- "Top Rated" becomes "Top Endorsed"
- Endorsement-specific language

The widget functionality remains identical—only labels change to match your selected mode.

## Troubleshooting

**Widget not appearing**

Verify that:
- Your theme supports widgets
- The widget area is active
- You've saved the widget settings
- Members have received reviews

**No members showing in BP Member Review Widget**

Check that:
- Members have approved reviews
- Review ratings exist in the database
- The member count is not too high

**BP Displayed Member Rating Widget is empty**

This widget only displays on member profile pages. Verify:
- You're viewing a member profile
- The member has received reviews
- Reviews are approved

**Styling conflicts**

If the widget looks broken:
1. Check for theme CSS conflicts
2. Use browser developer tools to inspect
3. Add custom CSS to override conflicts
4. Test with a default WordPress theme

## Related Documentation

- [Shortcodes](shortcodes.md) - Display reviews using shortcodes
- [Admin Settings Reference](../settings/admin-settings.md) - Configure review display
- [Managing Reviews](../settings/managing-reviews.md) - Approve and moderate reviews
