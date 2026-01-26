# Getting Started with BuddyPress Member Reviews

Add a review and rating system to your BuddyPress community. Members can rate each other using star ratings, write reviews, and build reputation within your community.

![Reviews on member profile](../images/frontend-reviews-tab.png)

## Requirements

- WordPress 5.0 or higher
- BuddyPress 5.0 or higher (or BuddyBoss Platform)
- PHP 7.4 or higher

## Installation

### From WordPress.org

1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "BuddyPress Member Reviews"
3. Click **Install Now**, then **Activate**

### Manual Installation

1. Download the plugin ZIP from WordPress.org
2. Go to **Plugins > Add New > Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Activate the plugin

## Initial Setup

After activation, configure the plugin at **Settings > Member Reviews**.

### Step 1: Configure Who Can Review

![General settings tab](../images/admin-settings-general.png)

In the **General** tab:

1. **Select member roles to write reviews** - Choose which user roles can submit reviews (e.g., Subscriber, Editor)
2. **User roles to accept reviews** - Choose which roles can receive reviews

Common configurations:

| Community Type | Who Writes | Who Receives |
|---------------|------------|--------------|
| Freelancer Marketplace | All Members | Service Providers |
| Professional Network | All Members | All Members |
| Service Directory | Customers | Business Owners |

### Step 2: Set Up Review Criteria

![Criteria settings tab](../images/admin-settings-criteria.png)

In the **Criteria** tab:

1. Enable **Allow Multiple Criteria** to rate on specific aspects
2. Click **Add Criteria** to create rating fields (e.g., "Communication", "Quality", "Timeliness")
3. Drag to reorder criteria as needed

If you skip this step, members will see a single overall rating field.

### Step 3: Configure Moderation

In the **General** tab:

| Setting | Recommended |
|---------|-------------|
| Auto approve reviews | Off - Review before publishing |
| Enable owner replies | On - Let members respond to reviews |
| Enable review flagging | On - Let users report issues |
| Auto-hide flag threshold | 5 flags |

### Step 4: Customize Appearance

In the **Display** tab:

1. **Review Label** - Change "Review" to match your community (e.g., "Testimonial", "Feedback")
2. **Rating Color** - Set star color to match your theme

## How Reviews Work

### For Members Writing Reviews

![Add review form](../images/frontend-add-review-form.png)

1. Visit another member's profile
2. Click the **Reviews** tab
3. Click **Add Review** (or the profile header button)
4. Rate each criterion using stars (1-5)
5. Write an optional text review
6. Submit

### Editing Your Review

If editing is enabled (Settings > General > Update Review), you can edit reviews you've written:

1. Go to the member's profile where you left a review
2. Find your review in the **Reviews** tab
3. Click **Edit Review** button (appears only on your own reviews)
4. A modal opens with your existing rating and text
5. Adjust the star ratings or update your written feedback
6. Click **Update Review**
7. Page refreshes with your updated review

**Note:** Only the review author sees the Edit button. Edits are immediate — no re-approval required.

---

### Reporting Inappropriate Reviews

If you see a review that violates community guidelines:

1. Click the **Report** button (flag icon) below the review
2. A modal opens asking for a reason
3. Select one of the predefined reasons:
   - Spam or fake review
   - Harassment or abuse
   - Inappropriate content
   - Conflict of interest
4. Click **Submit Report**
5. Button changes to "Reported ✓"

**What happens next:**
- Report count increases for the review
- Administrators see a report indicator in the admin panel
- If the review reaches the threshold (default: 5 reports), it's auto-hidden
- Administrators can dismiss reports or remove the review

---

### For Members Receiving Reviews

1. Get notified when someone reviews your profile (email + BuddyPress notification)
2. View reviews on your **Reviews** tab
3. Reply to reviews using the Owner Reply feature (see below)

### Replying to Reviews (Owner Reply)

As a profile owner, you can respond to reviews left on your profile:

1. View a review on your **Reviews** tab
2. Click **Reply to this review** button
3. An inline form appears below the review
4. Write your response
5. Click **Submit Reply**
6. Your reply appears with your avatar and "Owner Response" badge

**Notes:**
- You can only reply once per review
- Replies are immediate — no approval required
- If owner replies are disabled (Settings > General), the Reply button won't appear

### For Administrators

![Admin reviews list](../images/admin-reviews-list.png)

1. Pending reviews appear in **Reviews > All Reviews** (if moderation enabled)
2. Approve, edit, or trash reviews from the admin
3. Reported reviews show a report count for easy identification

## What's Next

- [Configure all admin settings](../admin-guide/settings.md)
- [Add the Top Members shortcode](../admin-guide/shortcodes.md)
- [Set up sidebar widgets](../admin-guide/widgets.md)

## Troubleshooting

### Reviews tab not appearing

- Ensure BuddyPress is active and member profiles are enabled
- Check that the user role is allowed to receive reviews
- Clear any caching plugins

### Email notifications not sending

- Verify WordPress can send emails (test with another plugin)
- Check the "Emails" setting is enabled in General tab
- Review your email server logs

### Ratings not calculating

- Ratings only calculate for published reviews
- Draft/pending reviews don't affect the average
- After approving a review, the rating updates automatically
