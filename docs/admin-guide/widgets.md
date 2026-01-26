# Widgets

BuddyPress Member Reviews includes two widgets for displaying reviews in sidebars and widget areas.

## Available Widgets

### BP Member Review Widget

Displays a list of top-rated or most-reviewed members site-wide.

**Best for:** Homepage sidebars, footer areas, anywhere you want to showcase your top members.

### BP Displayed Member Rating Widget

Shows reviews for the currently viewed member's profile.

**Best for:** BuddyPress member profile sidebars (appears only on member profile pages).

## BP Member Review Widget

### Settings

| Setting | Options | Description |
|---------|---------|-------------|
| Enter Title | Text | Widget heading |
| Display Members | Number | How many members to show |
| Top Rated / Most Reviewed | Radio buttons | Sort order |
| Display Avatar | Show/Hide | Member profile photos |

### Configuration

1. Go to **Appearance > Widgets**
2. Find "BP Member Review Widget"
3. Drag to your desired widget area
4. Configure settings:
   - **Title:** "Top Rated Members" (or your preference)
   - **Display Members:** 5-10 recommended
   - **Sorting:** Choose based on your priority
   - **Avatar:** Keep "Show" for visual appeal

### Output

Each member entry displays:

- Avatar (50x50 pixels)
- Display name (linked to profile)
- Star rating (visual + numeric)
- Review count

## BP Displayed Member Rating Widget

### When It Appears

This widget only shows content when viewing a BuddyPress member profile. On other pages, the widget area may appear empty.

### Settings

| Setting | Options | Description |
|---------|---------|-------------|
| Enter Title | Text | Widget heading (supports `[user-name]` placeholder) |
| Number of Ratings to Show | Number | How many reviews to display |
| Default Sorting | Latest/Highest/Lowest | Order of reviews |

### Configuration

1. Go to **Appearance > Widgets**
2. Find "BP Displayed Member Rating Widget"
3. Drag to your BuddyPress profile sidebar
4. Configure settings:
   - **Title:** "[user-name]'s Reviews" (placeholder gets replaced)
   - **Ratings to Show:** 3-5 for sidebars
   - **Sorting:** "Latest" for freshness, "Highest" for best foot forward

### Output

Each review entry displays:

- Reviewer avatar
- Reviewer name (or "Anonymous")
- Star rating
- Rating score

## Widget Areas

### Finding the Right Area

Common BuddyPress widget areas:

| Widget Area | Typical Location |
|-------------|-----------------|
| Member Profile Sidebar | Right side of member profiles |
| Homepage Sidebar | General sidebar |
| Footer Widgets | Site footer |

Your theme may have different names. Check **Appearance > Widgets** for available areas.

### BuddyBoss Theme

In BuddyBoss Platform:

1. Go to **Appearance > Widgets**
2. Look for "Profile - Right Sidebar"
3. Add the Displayed Member Rating Widget

### Reign Theme

In Reign Theme:

1. Go to **Appearance > Widgets**
2. Find "BP Member Profile"
3. Add widgets as needed

## Styling Widgets

### CSS Classes

**BP Member Review Widget:**
```css
.bupr_members_review_setting { }
.bupr-member-main { }
.bupr-members { }
.bupr-img-widget { }
.bupr-content-widget { }
.bupr-member-rating { }
.bupr-meta { }
```

**BP Displayed Member Rating Widget:**
```css
.bupr_single_member_rating_widget { }
#bp-member-rating { }
.item-list li { }
.item-avatar { }
.item-title { }
.item-meta { }
```

### Example Customizations

**Compact widget styling:**
```css
.bupr_single_member_rating_widget .item-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.bupr_single_member_rating_widget .item-avatar img {
    width: 40px;
    height: 40px;
}
```

**Remove avatars:**
```css
.bupr_members_review_setting .bupr-img-widget {
    display: none;
}
```

## Pro Mode Compatibility

Both widgets respect Pro plugin modes:

| Mode | Widget Behavior |
|------|----------------|
| Ratings | Shows star ratings |
| Testimonials | Hides stars, shows count only |
| Endorsements | Hides stars, shows endorsement count |

The widgets use these filters to adapt:

- `bupr_widget_show_stars` - Controls star display
- `bupr_item_type_label` - Changes "reviews" label

## Troubleshooting

### Widget not appearing

1. Check the widget is in an active widget area
2. Verify your theme supports that widget area
3. For the Displayed Member widget, ensure you're viewing a member profile

### No reviews showing

- Confirm at least one published review exists
- Check that the member role can receive reviews
- Verify the reviewed member has the correct role

### Styling conflicts

- Use browser inspector to identify conflicting CSS
- Add more specific selectors
- Use `!important` as last resort

### Performance on busy sites

- Limit displayed members/reviews to 5-10
- Consider using a caching plugin
- Widgets run database queries on each page load
