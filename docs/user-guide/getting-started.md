# Getting Started with BuddyPress Member Reviews

Add a review and rating system to your BuddyPress community. Members can rate each other using star ratings, write reviews, and build reputation within your community.

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

1. Visit another member's profile
2. Click the **Reviews** tab
3. Click **Add Review** (or the profile header button)
4. Rate each criterion using stars (1-5)
5. Write an optional text review
6. Submit

### For Members Receiving Reviews

1. Get notified when someone reviews your profile (email + BuddyPress notification)
2. View reviews on your **Reviews** tab
3. Reply to reviews (one reply per review)

### For Administrators

1. Pending reviews appear in **Reviews > All Reviews** (if moderation enabled)
2. Approve, edit, or trash reviews from the admin
3. Flagged reviews show a flag count for easy identification

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
