# Frequently Asked Questions

Common questions about BuddyPress Member Reviews.

## General

### Can members review themselves?

No. The plugin prevents self-reviews by default. Members can only review other members on the site.

### Can I limit who can be reviewed?

Yes. Go to **Settings → BuddyPress Member Reviews → General Settings** and select specific user roles in the "User roles allowed to get reviewed" option.

**Example:**

```bash
Enable only: Subscriber, Contributor
Result: Only users with these roles will have the Reviews tab
```

### Can I limit who can write reviews?

Yes. In **General Settings**, select user roles in the "User roles allowed to review" option.

**Example:**

```bash
Enable only: Customer, Subscriber
Result: Only these roles can submit reviews
```

### Is BuddyPress Member Reviews compatible with BuddyBoss?

Yes. The plugin works with both BuddyPress and BuddyBoss Platform. All features are fully compatible.

### Does it work with Multisite?

Yes. You can network activate the plugin or activate it per site. Settings are stored per site.

---

## Reviews

### How do I approve reviews?

Reviews appear as **Pending** by default (if auto-approval is disabled).

**To approve:**

```bash
1. Go to Reviews → All Reviews in WordPress admin
2. Find the pending review
3. Click Quick Edit → Change status to "Published"
4. Click Update
```

Or edit the review and click Publish.

### Can members edit their reviews?

Yes, if you enable it. Go to **Settings → BuddyPress Member Reviews → General Settings** and enable "Allow members to edit reviews".

Members can then edit their own reviews from the member profile page.

### Can members delete their reviews?

No. Only administrators can delete reviews to prevent abuse. Members can edit their reviews if that setting is enabled.

### What happens to ratings when a review is deleted?

The aggregate rating is automatically recalculated when a review is deleted. The member's rating and review count update immediately.

### Can I require admin approval for all reviews?

Yes. In **General Settings**, disable "Auto-approve reviews". All reviews will be pending until you publish them.

---

## Ratings

### How is the aggregate rating calculated?

The aggregate rating is the average of all review averages.

**Calculation:**

```bash
Step 1: Calculate each review's average
Review 1: (Quality:5 + Communication:4 + Service:5) / 3 = 4.67
Review 2: (Quality:4 + Communication:5 + Service:4) / 3 = 4.33

Step 2: Calculate member aggregate
Aggregate: (4.67 + 4.33) / 2 = 4.5
```

**Example with different criteria counts:**

```bash
Review 1: (Quality:5 + Communication:4) / 2 = 4.5
Review 2: (Quality:4 + Communication:5 + Service:5) / 3 = 4.67
Aggregate: (4.5 + 4.67) / 2 = 4.58
```

Each review contributes equally regardless of criteria count.

### What happens if I change the rating criteria?

Existing reviews preserve their original ratings. New reviews use the new criteria. The aggregate calculation uses available data from each review.

**Example:**

```bash
Old criteria: Quality, Communication
Existing Review 1: Quality:5, Communication:4
(Preserved)

New criteria: Quality, Communication, Speed
New Review 2: Quality:4, Communication:5, Speed:5

Both reviews calculate correctly in aggregate
```

### Can I use different criteria for different member types? (Pro)

Yes, with BuddyPress Member Reviews Pro. The Flexible Criteria feature lets you assign different rating criteria based on member types.

**Example:**

```bash
Member Type: Freelancer
Criteria: Quality, Communication, Timeliness

Member Type: Company
Criteria: Professionalism, Support, Value
```

---

## Display

### How do I change the Reviews tab name?

Go to **Settings → BuddyPress Member Reviews → Display Settings** and change the "Tab Label" field.

**Example:**

```bash
Change "Reviews" to "Testimonials"
```

Or use code:

```php
add_filter( 'bupr_review_tab_name', function( $name ) {
    return 'Testimonials';
} );
```

### How do I change the star color?

Go to **Display Settings** and use the color picker for "Star Rating Color".

### Can I hide the Reviews tab for certain members?

Yes, use the filter hook:

```php
add_filter( 'bupr_show_profile_review_tab', function( $show ) {
    // Hide for administrators
    if ( bp_is_user_member_of_group( array( 'administrator' ) ) ) {
        return false;
    }
    return $show;
} );
```

Or hide based on member type:

```php
add_filter( 'bupr_show_profile_review_tab', function( $show ) {
    $member_type = bp_get_member_type( bp_displayed_user_id() );
    if ( $member_type === 'vendor' ) {
        return false; // No reviews for vendors
    }
    return $show;
} );
```

### How do I customize the review layout?

Use template overrides. Copy the template file from the plugin to your theme:

**From:**
```bash
wp-content/plugins/buddypress-member-review/templates/bupr-reviews-tab-template.php
```

**To:**
```bash
wp-content/themes/your-theme/buddypress-member-review/bupr-reviews-tab-template.php
```

See the [Template Overrides](../developer-guide/templates.md) guide for details.

---

## Notifications

### Why are email notifications not sending?

Check these items:

```bash
1. Setting enabled:
   Settings → BuddyPress Member Reviews → Notifications
   "Send email notifications" is checked

2. WordPress email works:
   Install WP Mail SMTP plugin
   Send test email

3. Server allows mail:
   Check with hosting provider
   Enable SMTP if needed

4. Check spam folder:
   Emails may be filtered as spam
   Add site email to contacts

5. Debug mode:
   Enable WP_DEBUG_LOG in wp-config.php
   Check wp-content/debug.log for errors
```

### How do I customize notification emails?

Edit the email template in **Settings → BuddyPress Member Reviews → Notifications**.

**Available placeholders:**

```
[user-name]       - Name of person being reviewed
[reviewer-name]   - Name of person who left review
[review-link]     - Link to review on profile
[site-name]       - Your site name
[site-admin]      - Site administrator email
```

**Example template:**

```
Hi [user-name],

[reviewer-name] left you a review on [site-name]!

View your review: [review-link]
```

### Can I disable BuddyPress notifications but keep email?

Yes. Go to **Notifications Settings** and uncheck "Enable BuddyPress notifications" while keeping "Send email notifications" checked.

---

## Anonymous Reviews

### What information is hidden for anonymous reviews?

When a review is submitted anonymously:

- Reviewer name shows as "Anonymous Reviewer"
- Reviewer avatar shows default avatar
- Reviewer profile link is removed
- Reviewer identity hidden from public view

**Admins can still see** the real reviewer in WordPress admin.

### Can administrators see who wrote anonymous reviews?

Yes. In **Reviews → All Reviews**, administrators can see the actual author in the "Author" column.

### Do anonymous reviews post to Activity stream?

If Activity posting is enabled, anonymous reviews will show the **actual reviewer** in the activity stream, not "Anonymous".

**Solution:** Disable Activity posting for privacy:

```bash
Settings → BuddyPress Member Reviews → General Settings
Uncheck "Post to Activity Stream"
```

Or filter it:

```php
add_filter( 'bupr_allow_activity_posting', function( $allow ) {
    global $bupr_review_id;
    $is_anonymous = get_post_meta( $bupr_review_id, 'bupr_anonymous_review_post', true );
    if ( $is_anonymous === 'yes' ) {
        return false; // Don't post anonymous reviews to activity
    }
    return $allow;
} );
```

---

## BuddyPress Member Reviews Pro

### What features does Pro add?

BuddyPress Member Reviews Pro includes:

**Review Modes:**
- Free Mode: Any member can review anyone
- Review Mode: Reviews by request only
- Endorsement Mode: Skill endorsements only

**Verification System:**
- Verified reviewer badges
- Multiple verification types
- Custom verification criteria

**Flexible Rating Criteria:**
- Different criteria per member type
- Conditional criteria display
- Custom criteria management

**Skill Endorsements:**
- Endorse specific skills
- Skill tags on reviews
- Skill-based filtering

**REST API:**
- Full API access
- Submit/retrieve reviews via API
- Integration with external apps

**Advanced Features:**
- Review requests with notifications
- Review reminders
- Bulk review management
- Enhanced reporting

### Do I need Pro for basic reviews?

No. The free version provides full review functionality:

- Multi-criteria ratings
- Anonymous reviews
- Review moderation
- Email notifications
- Activity stream integration
- Widgets and shortcodes

Pro adds advanced workflows and verification features.

### Can I upgrade from free to Pro later?

Yes. Your existing reviews, ratings, and settings are preserved. Simply install Pro and activate your license key.

**Upgrade process:**

```bash
1. Purchase Pro license
2. Download Pro plugin
3. Deactivate free version
4. Upload and activate Pro version
5. Enter license key
6. All data is preserved
```

---

## Technical

### What are the minimum requirements?

```bash
WordPress: 5.0 or higher
BuddyPress: 5.0 or higher (or BuddyBoss Platform)
PHP: 7.4 or higher
MySQL: 5.6 or higher
```

### Does it work with page builders?

Yes. The plugin works with:

- Elementor (use BuddyPress widgets)
- Beaver Builder
- Divi
- WPBakery

Use the `[bupr_display_top_members]` shortcode in page builders.

### Does it support RTL languages?

Yes. The plugin is RTL-ready and includes .pot file for translations.

### Can I translate the plugin?

Yes. Use Loco Translate plugin or edit the .po file in `/languages/` directory.

**Translation files:**

```bash
buddypress-member-review.pot  - Template
buddypress-member-review-es_ES.po - Spanish example
```

### Does it work with custom BuddyPress profiles?

Yes. The plugin hooks into standard BuddyPress profile tabs. Custom profile plugins that maintain BuddyPress structure will work.

---

## Related Documentation

- [Installation Guide](../getting-started/installation.md)
- [Settings Overview](../settings/general-settings.md)
- [Troubleshooting](troubleshooting.md)
- [Hooks & Filters](../developer-guide/hooks-and-filters.md)
