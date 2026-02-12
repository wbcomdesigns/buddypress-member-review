# Pro Features Overview

Upgrade to BuddyPress Member Reviews Pro for advanced review management, flexible terminology, and enhanced functionality.

![Pro Features Overview](../images/pro-features-full-page.png)

## What's Included in Pro

The Pro version adds professional-grade features for community sites, business directories, and membership platforms.

**Key Additions:**

- **Review Modes** - Switch between Reviews, Testimonials, or Endorsements
- **Verified Reviewer Badges** - Mark trusted reviewers with verified badges
- **Flexible Criteria** - Advanced multi-criteria management with custom weighting
- **Member Skills** - Track and display member skills with ratings
- **REST API** - Integrate reviews with external apps and services
- **Admin Dashboard** - Enhanced review management and analytics
- **Priority Support** - Fast email support with priority handling
- **Regular Updates** - New features and improvements

## Free vs Pro Comparison

| Feature | Free | Pro |
|---------|------|-----|
| Submit member reviews | ✓ | ✓ |
| Star ratings (1-5) | ✓ | ✓ |
| Review approval workflow | ✓ | ✓ |
| Email notifications | ✓ | ✓ |
| Anonymous reviews | ✓ | ✓ |
| Review flag/report system | ✓ | ✓ |
| Owner replies | ✓ | ✓ |
| Top members shortcode | ✓ | ✓ |
| Member review widgets | ✓ | ✓ |
| Multi-criteria ratings | ✓ | ✓ |
| **Review modes (Testimonials/Endorsements)** | ✗ | ✓ |
| **Verified reviewer badges** | ✗ | ✓ |
| **Advanced criteria weighting** | ✗ | ✓ |
| **Member skills tracking** | ✗ | ✓ |
| **Skills shortcode** | ✗ | ✓ |
| **REST API endpoints** | ✗ | ✓ |
| **Enhanced admin dashboard** | ✗ | ✓ |
| **Review analytics** | ✗ | ✓ |
| **Priority support** | ✗ | ✓ |

## Review Modes

Transform your review system to match your site's purpose with three distinct modes.

**Review Mode (Default)**
- Standard review terminology
- Best for: Community sites, general feedback systems
- Labels: "Review", "Reviews", "Reviewer"

**Testimonial Mode**
- Client testimonial terminology
- Best for: Business directories, service provider sites, portfolios
- Labels: "Testimonial", "Testimonials", "Client"

**Endorsement Mode**
- Professional endorsement terminology
- Best for: Professional networks, LinkedIn-style sites, business connections
- Labels: "Endorsement", "Endorsements", "Endorsed by"

**Switching Modes:**

All features work identically across modes—only terminology changes. Switch modes at **Settings > BP Member Review > Review Mode**.

**Changes Automatically:**
- All frontend labels
- Email notification text
- Admin menu items
- Shortcode output
- Widget labels
- Activity stream posts

## Verified Reviewer Badges

Mark trusted reviewers with verified badges that appear next to their reviews.

**Badge Display:**
- Verified checkmark icon next to reviewer name
- Tooltip: "Verified Reviewer"
- Appears on all reviews by verified members
- Increases review credibility

**Setting Verified Status:**

**Per Member:**
1. Go to **Users > All Users**
2. Edit the member's profile
3. Check **Verified Reviewer**
4. Click **Update User**

**Bulk Verification:**
1. Select multiple users in user list
2. Choose **Set as Verified Reviewer** from bulk actions
3. Click **Apply**

**Use Cases:**
- Mark paying customers
- Verify real transactions
- Identify trusted community members
- Highlight expert reviewers

## Flexible Criteria

Pro includes a dynamic criteria builder for creating custom rating fields.

**What You Can Do:**

- Add, remove, and reorder rating criteria
- Customize criteria labels and descriptions
- Configure which criteria appear on review forms
- Build criteria sets that match your use case

**Configuring Criteria:**

1. Go to **Settings > BP Member Review > Criteria**
2. Use the Pro criteria builder interface
3. Add or remove criteria as needed
4. Save changes

## Endorsements

Pro includes an Endorsement Mode that transforms reviews into skill-based endorsements.

**What It Does:**

- Switch from ratings to skill endorsements
- Members endorse each other for specific skills
- Skills display on member profiles
- Perfect for professional networks and business directories

**How to Enable:**

1. Go to **Settings > BP Member Review > Review Mode**
2. Select **Endorsements**
3. Configure endorsement settings
4. Save changes

**Use Cases:**
- Professional networks
- Business directories
- Team collaboration sites
- Skill-based communities

## REST API

Integrate review data with external applications, mobile apps, and custom dashboards.

![Pro Review Management](../images/pro-review-management.png)

**Available Endpoints:**

**GET Endpoints:**
- `/wp-json/bupr/v1/reviews` - List all reviews
- `/wp-json/bupr/v1/reviews/{id}` - Get single review
- `/wp-json/bupr/v1/members/{id}/reviews` - Get member reviews
- `/wp-json/bupr/v1/members/{id}/rating` - Get member rating
- `/wp-json/bupr/v1/top-members` - Get top-rated members

**POST Endpoints:**
- `/wp-json/bupr/v1/reviews` - Submit new review
- `/wp-json/bupr/v1/reviews/{id}/reply` - Add owner reply
- `/wp-json/bupr/v1/reviews/{id}/flag` - Flag review

**Authentication:**

Requires WordPress REST API authentication:
- Application passwords (recommended)
- OAuth 1.0a
- JWT tokens

**Enabling API:**

1. Go to **Settings > BP Member Review > API**
2. Check **Enable REST API**
3. Configure authentication method
4. Generate API keys (if using key authentication)
5. Save changes

**Use Cases:**
- Mobile app integration
- Custom frontend displays
- Third-party service integration
- Automated review imports
- Analytics dashboards

## Enhanced Admin Dashboard

Pro adds a comprehensive review management dashboard with analytics and quick actions.

**Dashboard Features:**

**Review Statistics:**
- Total reviews count
- Pending approval count
- Average site rating
- Reviews per day/week/month
- Top-rated members

**Quick Actions:**
- Approve pending reviews
- View flagged reviews
- Export review data
- Bulk moderation tools

**Analytics Charts:**
- Reviews over time
- Rating distribution
- Top reviewers
- Most reviewed members
- Criteria rating breakdown

**Accessing Dashboard:**

Go to **Reviews > Dashboard** in WordPress admin.

**Customization:**

- Drag to reorder dashboard widgets
- Hide/show widgets via screen options
- Set date ranges for analytics
- Export data as CSV

## Priority Support

Pro license includes fast, priority email support.

**What's Included:**

- **Response Time:** Within 24 hours (weekdays)
- **Support Channels:** Email, support ticket system
- **Scope:** Plugin configuration, troubleshooting, customization guidance
- **Language:** English

**How to Get Support:**

1. Visit the support portal (provided with purchase)
2. Submit a ticket with:
   - WordPress version
   - Plugin version
   - Description of issue
   - Screenshots (if applicable)
   - Steps to reproduce
3. Receive response within 24 hours

**Not Included:**
- Custom development
- Theme compatibility fixes (unless caused by plugin)
- Server configuration
- Other plugin conflicts

## Getting Pro

### Requirements

**Before upgrading, ensure you have:**

- BuddyPress Member Reviews (free version) installed
- WordPress 5.0 or higher
- BuddyPress 5.0 or higher
- PHP 7.4 or higher
- Active WordPress site

### Installation

**If you have the free version:**

1. Keep the free version activated
2. Install Pro plugin (separate download)
3. Activate Pro plugin
4. Pro features activate automatically
5. Free version remains active (required)

**Fresh installation:**

1. Install and activate free version first
2. Then install and activate Pro
3. Configure Pro settings

### License Activation

**Important:** Pro features work without license activation. The license is only required for:

- Automatic updates
- Support ticket access
- Future feature updates

**Activating License:**

1. Go to **Settings > BP Member Review > License**
2. Enter your license key (from purchase email)
3. Click **Activate License**
4. Status changes to "Active"

**License Details:**

- **1 Site License:** Use on one domain
- **5 Site License:** Use on up to five domains
- **Unlimited License:** Use on unlimited domains
- **Renewals:** Annual renewal for updates and support

### Upgrading from Free

Your existing data is preserved:

- All reviews remain intact
- Member ratings are preserved
- Settings carry over
- No configuration needed

**After Activating Pro:**

1. Visit **Settings > BP Member Review**
2. New Pro tabs appear (Review Mode, Skills, API, Dashboard)
3. Configure Pro features as needed
4. All existing features continue working

## Feature Documentation

Each Pro feature has dedicated documentation:

- **Review Modes:** Configure testimonial and endorsement modes
- **Verified Badges:** Set up verified reviewer system
- **Criteria Weighting:** Advanced multi-criteria configuration
- **Member Skills:** Skills tracking and display
- **REST API:** API endpoints and authentication
- **Admin Dashboard:** Using the analytics dashboard

## Frequently Asked Questions

**Does Pro replace the free version?**

No. Pro requires the free version to be installed. They work together.

**Can I switch between modes after collecting reviews?**

Yes. Switching modes only changes labels—all review data remains intact.

**Do I need to activate the license immediately?**

No. Pro features work without license activation. License is only for updates and support.

**Can I upgrade my license later?**

Yes. You can upgrade from 1 site to 5 sites or unlimited at any time.

**What happens if my license expires?**

Pro features continue working. You lose access to updates and support until renewal.

**Can I use Pro on a staging site?**

Yes. Use the same license on staging and production (counts as one site).

## Related Documentation

- [Admin Settings Reference](admin-settings.md) - Configure all plugin settings
- [Managing Reviews](managing-reviews.md) - Review moderation workflow
- [Shortcodes](../shortcodes-widgets/shortcodes.md) - Display reviews with shortcodes
- [Widgets](../shortcodes-widgets/widgets.md) - Review widgets for sidebars
