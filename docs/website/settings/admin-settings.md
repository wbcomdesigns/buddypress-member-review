# Admin Settings Reference

Complete guide to all BuddyPress Member Reviews plugin settings. Access settings at **Settings > BP Member Review** in your WordPress admin.

![Admin Settings General Tab](../images/admin-settings-general.png)

## General Settings Tab

### Review Submission Settings

**Enable Multi Reviews**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Allow members to submit multiple reviews for the same person. When disabled, each member can only review another member once.

**Display Submit Review Button**
- **Option:** Yes/No
- **Default:** No
- **Description:** Show the "Submit Review" button on member profiles. Disable to hide the review form (useful if you want to limit who can submit reviews).

**Auto Approve Member Review**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Automatically approve reviews when submitted. When disabled, reviews require admin approval before appearing on profiles.

**Enable Review Update**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Allow members to edit their own reviews after submission. Works with both single and multi-review modes.

### Display Settings

**Display Member Rating in Members Directory**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Show member ratings on the main members directory page. Helps visitors quickly identify top-rated members.

**Number of Reviews Per Page**
- **Option:** Number
- **Default:** 5
- **Description:** How many reviews to display per page on member profiles before pagination appears. Set between 1-50.

### User Role Permissions

**Select Member Roles to Write Reviews**
- **Option:** Multi-select dropdown
- **Options:** Administrator, Editor, Author, Contributor, Subscriber, Custom Roles
- **Default:** All roles selected
- **Description:** Choose which user roles are allowed to submit reviews. Only selected roles can write reviews on member profiles.

**User Roles to Accept Reviews**
- **Option:** Multi-select dropdown
- **Options:** Administrator, Editor, Author, Contributor, Subscriber, Custom Roles
- **Default:** All roles selected
- **Description:** Choose which user roles can receive reviews on their profiles. Only members with selected roles will have the review section visible on their profile.

### Email Notification Settings

**Send Email on Review Submission**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Email the reviewed member when someone submits a review about them.

**Review Notification Email Subject**
- **Field:** Text
- **Default:** "You Have Received a New Review on [site-name]"
- **Description:** Subject line for review notification emails. Use `[site-name]` to dynamically insert your website name.

**Review Notification Email Message**
- **Field:** Textarea
- **Default:** Pre-formatted message
- **Description:** Email body sent to reviewed members. Use placeholders for dynamic content.

**Available Placeholders:**
- `[user-name]` - Name of the reviewed member
- `[reviewer-name]` - Name of the person who wrote the review
- `[review-link]` - Link to view the review
- `[site-name]` - Your website name
- `[site-admin]` - Site administrator name

**Example:**

```
Hi [user-name],

[reviewer-name] has left you a review.

View the review here: [review-link]

Thanks,
[site-name]
```

### Approval Email Settings

These settings appear when **Auto Approve Member Review** is set to No. They control the email sent to the site administrator when a new review requires manual approval.

**Approval Email Subject**
- **Field:** Text
- **Default:** "A New Review Requires Your Approval on [site-name]"
- **Description:** Subject line for the admin notification email when a review is submitted and awaits approval.

**Approval Email Message**
- **Field:** Textarea
- **Default:** Pre-formatted message
- **Description:** Email body sent to the site administrator for review approval. Use placeholders for dynamic content.

**Available Placeholders:**
- `[review-aproval-link]` - Link to the admin approval page for the review
- `[site-admin]` - Site administrator name
- `[site-name]` - Your website name

### BuddyPress Notifications

**BuddyPress Notifications**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Enable or disable BuddyPress notifications when someone receives a review. When enabled, members see a notification in their BuddyPress notification dropdown alerting them about new reviews on their profile.

### Privacy and Moderation Settings

**Enable Anonymous Review**
- **Option:** Yes/No
- **Default:** No
- **Description:** Allow members to submit reviews anonymously. The reviewer's name will be hidden, showing only "Anonymous" on the review.

**Post Review to Activity Stream**
- **Option:** Yes/No
- **Default:** No
- **Description:** Create an activity post when a review is submitted. Shows in the BuddyPress activity feed.

**Enable Review Owner Reply**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Let reviewed members reply to reviews left on their profile. Replies appear beneath the original review.

**Enable Review Flag/Report**
- **Option:** Yes/No
- **Default:** Yes
- **Description:** Allow members to flag inappropriate reviews. Flagged reviews appear in the admin for moderation.

**Review Flag Threshold**
- **Option:** Number
- **Default:** 5
- **Description:** Automatically hide reviews after receiving this many flags. The review remains in the admin for review but disappears from the frontend.

## Criteria Settings Tab

![Admin Settings Criteria Tab](../images/admin-settings-criteria.png)

### Enable Multi Criteria

**Enable Multiple Criteria**
- **Option:** Yes/No
- **Default:** No
- **Description:** Allow reviews to rate members across multiple criteria (e.g., Communication, Reliability, Professionalism). When disabled, only a single overall rating is used.

### Managing Criteria

**Adding New Criteria:**

1. Click **Add New Criterion**
2. Enter criterion name (e.g., "Communication Skills")
3. Check **Active** to enable immediately
4. Click **Save Changes**

**Editing Criteria:**

1. Locate the criterion in the list
2. Click the name to edit inline
3. Toggle **Active** checkbox to enable/disable
4. Click **Save Changes**

**Reordering Criteria:**

1. Hover over a criterion row
2. Click and drag the handle icon (⋮⋮)
3. Drop in the desired position
4. Click **Save Changes**

**Deleting Criteria:**

1. Uncheck **Active** to disable
2. Click **Delete** icon
3. Confirm deletion
4. Click **Save Changes**

**Note:** Deleting criteria removes associated ratings from existing reviews.

### Default Criteria Examples

- **Communication:** How well the member communicates
- **Reliability:** Dependability and consistency
- **Quality:** Quality of work or contributions
- **Professionalism:** Professional behavior and conduct
- **Friendliness:** Approachability and attitude

## Display Settings Tab

![Admin Settings Display Tab](../images/admin-settings-display.png)

### Review Labels

**Review Label (Singular)**
- **Field:** Text
- **Default:** "Review"
- **Description:** Singular form used throughout the plugin (e.g., "Submit Review", "1 Review").

**Review Label (Plural)**
- **Field:** Text
- **Default:** "Reviews"
- **Description:** Plural form used throughout the plugin (e.g., "5 Reviews", "All Reviews").

**Common Label Customizations:**
- Testimonials: "Testimonial" / "Testimonials"
- Endorsements: "Endorsement" / "Endorsements"
- Ratings: "Rating" / "Ratings"
- Feedback: "Feedback" / "Feedback"
- References: "Reference" / "References"

### Rating Color

**Star Rating Color**
- **Field:** Color picker
- **Default:** #FFC400 (Gold)
- **Description:** Color used for filled star ratings throughout your site. Affects member profiles, directories, widgets, and shortcodes.

**Color Selection Tips:**
- Use your brand color for consistency
- Ensure sufficient contrast with backgrounds
- Test on both light and dark areas
- Common choices: Gold (#FFC400), Orange (#FF9800), Blue (#2196F3), Red (#F44336)

## Shortcode Tab

Reference for all available shortcodes. See [Shortcodes](../shortcodes-widgets/shortcodes.md) for complete reference.

## Pro Features Tab

![Admin Settings Pro Features](../images/admin-settings-pro-features.png)

Preview of Pro features available when you upgrade. See the [Pro Features Overview](pro-features.md) for complete details.

**Pro Features Include:**
- Review modes (Testimonials, Endorsements)
- Verified reviewer badges
- Flexible criteria management
- Member skills tracking
- REST API endpoints
- Advanced admin dashboard
- Priority support

**Access Pro Settings:**

After upgrading to Pro, additional settings tabs appear:
- **Review Mode:** Switch between Review, Testimonial, and Endorsement modes
- **Skills:** Configure member skills tracking
- **API:** Enable and manage REST API access
- **Dashboard:** Configure the admin review dashboard

## Saving Settings

**Important:** Always click **Save Changes** at the bottom of each tab before switching tabs. Settings are saved per-tab, not globally.

**Verification:**

After saving, you'll see a success message: "Settings saved successfully."

## Resetting to Defaults

To reset settings to default values:

1. Deactivate the plugin at **Plugins > Installed Plugins**
2. Activate the plugin again
3. Previous settings remain in the database

To completely reset (including removing all reviews):

1. Deactivate the plugin
2. Delete the plugin
3. Reinstall from scratch

**Warning:** This permanently deletes all reviews and ratings.

## Related Documentation

- [Managing Reviews in Admin](managing-reviews.md) - Approve and moderate reviews
- [Shortcodes](../shortcodes-widgets/shortcodes.md) - Display reviews with shortcodes
- [Widgets](../shortcodes-widgets/widgets.md) - Add review widgets to sidebars
- [Pro Features Overview](pro-features.md) - Learn about Pro capabilities
