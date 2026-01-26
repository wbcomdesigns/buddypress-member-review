# Frequently Asked Questions

Common questions about BuddyPress Member Reviews.

## General Questions

### Can members review themselves?

No. The plugin automatically prevents self-reviews. The current user is excluded from the reviewable members list.

### Can I limit who can be reviewed?

Yes. In **Settings > Member Reviews > General**, use "User roles to accept reviews" to specify which roles can receive reviews. For example, only allow reviews on "Service Provider" or "Freelancer" roles.

### Can I limit who can write reviews?

Yes. Use "Select member roles to write reviews" to control which user roles can submit reviews.

### Do reviews work with BuddyBoss?

Yes. The plugin is compatible with both BuddyPress and BuddyBoss Platform.

## Review Management

### How do I approve reviews?

If "Auto approve reviews" is disabled:

1. Go to **Reviews** in the WordPress admin
2. Find reviews with "Pending" status
3. Hover and click **Approve**, or click to edit and change status to Published

### Can members edit their reviews?

Only if you enable "Update Review" in General settings. When enabled, reviewers see an Edit button on their own reviews.

### Can members delete their reviews?

Currently, members cannot delete their own reviews. Only administrators can trash reviews from the admin panel.

### What happens to ratings when a review is deleted?

The member's aggregate rating is automatically recalculated, excluding the deleted review.

## Ratings & Criteria

### How is the aggregate rating calculated?

For each review, the plugin averages all criteria ratings to get a review score. Then it averages all review scores to get the member's aggregate rating.

**Example:**
- Review 1: Communication (5), Quality (4), Timeliness (5) = 4.67 average
- Review 2: Communication (4), Quality (5), Timeliness (4) = 4.33 average
- Member aggregate: (4.67 + 4.33) / 2 = 4.50

### What if I change the rating criteria?

Existing reviews keep their original criteria data. New reviews use the updated criteria. The aggregate calculation handles this gracefully by averaging whatever numeric values exist in each review.

### Can I have different criteria for different user types?

Not in the free version. With the Pro addon's Flexible Criteria feature, you can create different criteria sets.

## Display & Customization

### How do I change the Reviews tab name?

In **Settings > Member Reviews > Display**, change the "Review" and "Review (Plural)" labels.

### How do I change the star color?

In **Settings > Member Reviews > Display**, use the Rating Color picker.

### Can I hide the Reviews tab for certain members?

Yes, using a code snippet:

```php
add_filter( 'bupr_show_profile_review_tab', function( $show, $user_id ) {
    // Hide for specific user IDs
    $hidden_users = array( 1, 5, 10 );
    if ( in_array( $user_id, $hidden_users ) ) {
        return false;
    }
    return $show;
}, 10, 2 );
```

### How do I customize the review layout?

Copy templates from `wp-content/plugins/buddypress-member-review/includes/templates/` to `wp-content/themes/your-theme/review/` and edit them. See the [Templates Guide](../developer-guide/templates.md).

## Notifications

### Why aren't email notifications sending?

Check:
1. "Emails" is enabled in General settings
2. WordPress can send emails (test with another plugin)
3. Your hosting doesn't block wp_mail()
4. Email isn't going to spam

### Can I customize notification emails?

Yes. In General settings, you can edit the email subject and message. Use placeholders like `[user-name]`, `[reviewer-name]`, `[review-link]`, and `[site-name]`.

### How do I disable BuddyPress notifications?

Turn off "BuddyPress Notifications" in General settings.

## Anonymous Reviews

### What information is hidden in anonymous reviews?

Anonymous reviews hide:
- Reviewer's name (shows "Anonymous")
- Reviewer's avatar (shows default/generic avatar)

The review content and ratings are still visible.

### Can admins see who wrote anonymous reviews?

Yes. Administrators can see the actual author in the WordPress admin when editing the review.

### Does activity show anonymous reviews?

By default, activity posts include the actual reviewer. To fully anonymize, disable "Enable review activity" in settings.

## Reporting & Moderation

### What are the report reasons?

- Spam or fake review
- Harassment or abuse
- Inappropriate content
- Conflict of interest

### What happens when a review gets reported?

1. Report count increases
2. Admin sees a report indicator in the review list
3. If threshold is reached, review is auto-hidden

### How do I dismiss reports?

Edit the reported review and use the "Dismiss Reports" option in the Report Details section.

### Can reported reviews be restored?

Yes. Auto-hidden reviews are still in the database. Change their status back to Published to restore them.

## Shortcode & Widgets

### Why doesn't the shortcode show any members?

The shortcode only shows members who have:
- At least one published review
- A calculated aggregate rating

If no members have been reviewed yet, it shows "No members have been reviewed yet."

### Can I show the shortcode on specific pages only?

Use conditional logic in your theme:

```php
if ( is_page( 'top-members' ) ) {
    echo do_shortcode( '[bupr_display_top_members]' );
}
```

### Why is the widget empty?

The "BP Displayed Member Rating Widget" only shows content on BuddyPress member profile pages. On other pages, it displays nothing.

## Pro Features

### What does the Pro version add?

- **Review Modes**: Testimonials (no ratings) and Endorsements (skill-based)
- **Verified Badges**: Mark trustworthy reviews
- **Flexible Criteria**: Thumbs up/down and text fields beyond stars
- **REST API**: Full API for mobile apps and integrations
- **Endorsement Mode**: LinkedIn-style skill endorsements

### Do I need Pro for basic reviews?

No. The free version includes full review functionality: star ratings, multiple criteria, owner replies, reporting, notifications, shortcodes, and widgets.

### Can I upgrade from Free to Pro later?

Yes. Pro is an addon that works alongside the free version. Your existing reviews are preserved.

## Troubleshooting

### Reviews tab not showing

1. Ensure BuddyPress is active
2. Check member profiles are enabled in BuddyPress settings
3. Verify the user's role is in "User roles to accept reviews"
4. Clear any caching plugins

### Ratings not calculating

1. Ensure reviews are Published (not Draft/Pending)
2. Try editing and re-saving a review to trigger recalculation
3. Check if the rating criteria have values

### Activity not posting

1. Enable "Enable review activity" in settings
2. Ensure BuddyPress Activity component is active
3. Check for JavaScript errors in browser console

### Plugin conflicts

Disable other BuddyPress plugins one by one to identify conflicts. Common issues:
- Other review/rating plugins
- Caching plugins (try clearing cache)
- Security plugins blocking AJAX
