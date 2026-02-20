=== Wbcom Designs - BuddyPress Member Reviews ===
Contributors: wbcomdesigns, vapvarun
Donate link: https://wbcomdesigns.com/donate/
Tags: buddypress, members, reviews, ratings, community
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 3.7.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Let BuddyPress members review and rate each other. Build trust in your community with star ratings, multiple criteria, and review moderation.

== Description ==

**Build trust between members with peer reviews.**

BuddyPress Member Reviews lets registered members post reviews on other members' profiles. Rate members using customizable criteria, display aggregate ratings, and moderate feedback - all from your WordPress dashboard.

[vimeo https://vimeo.com/556813504]

= What You Can Do =

**For Members:**
- Write reviews with star ratings (1-5 stars)
- Rate using multiple criteria (Communication, Skills, etc.)
- Submit reviews anonymously when enabled
- Reply to reviews on your own profile
- Report inappropriate reviews

**For Admins:**
- Create custom rating criteria
- Require approval before reviews go live
- Auto-hide reviews after X reports
- Send email notifications for new reviews
- Display top-rated members in a widget

= Key Features =

- **Multiple Criteria** - Rate members on Communication, Reliability, or any criteria you create
- **Owner Replies** - Profile owners can respond to reviews once
- **Report System** - Members can report inappropriate reviews
- **Anonymous Reviews** - Let members submit feedback privately
- **Review Moderation** - Approve reviews before they appear
- **Top Members Widget** - Showcase highest-rated members
- **Shortcode Support** - Display reviews anywhere with `[bupr_display_top_members]`
- **BuddyBoss Compatible** - Works with BuddyBoss Platform and Theme

= Need More? =

**[BuddyPress Member Review Pro](https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro/)** adds three review modes, verified badges, and a REST API:

- **Testimonials Mode** - Text-only reviews without star ratings
- **Endorsements Mode** - LinkedIn-style skill endorsements
- **Verified Badges** - Mark trusted reviews with visual badges
- **Flexible Criteria** - Stars, thumbs up/down, or text responses
- **REST API** - Build mobile apps or custom integrations
- **Weighted Ratings** - Control how criteria affect aggregate scores

[Learn more about Pro](https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro/)

= Links =

- [Plugin Page](https://wbcomdesigns.com/downloads/buddypress-member-reviews/)
- [Documentation](https://docs.wbcomdesigns.com/docs/buddypress-member-review/)
- [Support](https://wbcomdesigns.com/support/)

= Theme Recommendation =

**[BuddyX Theme](https://wordpress.org/themes/buddyx/)** - Free BuddyPress theme with built-in support for Member Reviews.

= More BuddyPress Plugins =

Free plugins by Wbcom Designs:

- **[BuddyPress Group Reviews](https://wordpress.org/plugins/review-buddypress-groups/)** - Rate and review groups
- **[BuddyPress Activity Social Share](https://wordpress.org/plugins/bp-activity-social-share/)** - Share activities on social networks
- **[Private Community with BP Lock](https://wordpress.org/plugins/lock-my-bp/)** - Make your community private
- **[BuddyPress Job Manager](https://wordpress.org/plugins/bp-job-manager/)** - Job listings in BuddyPress
- **[Check-ins for BuddyPress](https://wordpress.org/plugins/bp-check-in/)** - Location check-ins
- **[Shortcodes for BuddyPress](https://wordpress.org/plugins/shortcodes-for-buddypress/)** - Display members and activity anywhere

Premium plugins:

- **[BuddyPress Hashtags](https://wbcomdesigns.com/downloads/buddypress-hashtags/)**
- **[BuddyPress Polls](https://wbcomdesigns.com/downloads/buddypress-polls/)**
- **[BuddyPress Status & Reaction](https://wbcomdesigns.com/downloads/buddypress-status/)**
- **[BuddyPress Profanity](https://wbcomdesigns.com/downloads/buddypress-profanity/)**

== Installation ==

1. Upload `buddypress-member-review` to `/wp-content/plugins/`
2. Activate through the Plugins menu
3. Go to **Reviews > Settings** to configure

= Quick Setup =

1. **General Tab** - Enable notifications and set permissions
2. **Criteria Tab** - Add rating criteria (Communication, Skills, etc.)
3. **Display Tab** - Customize labels and colors

== Frequently Asked Questions ==

= How do members submit reviews? =

Members visit another member's profile and click the "Reviews" tab. They'll see an "Add Review" button to submit their rating.

= Can members review themselves? =

No. The plugin prevents self-reviews to ensure honest feedback.

= How do I add custom rating criteria? =

Go to **Reviews > Settings > Criteria Tab**. Add criteria like "Communication," "Reliability," or any label that fits your community.

= Can I require approval before reviews appear? =

Yes. Enable "Admin Approval Required" in General Settings. Reviews stay in draft until you approve them.

= Does it work with BuddyBoss? =

Yes. Tested with BuddyBoss Platform and BuddyBoss Theme.

= How do I display top-rated members? =

Use the "Top Members" widget in any widget area, or use the `[bupr_display_top_members]` shortcode.

= Can members reply to reviews about them? =

Yes. Profile owners can reply to reviews once. Enable this in General Settings.

= What's the difference between Free and Pro? =

Free includes star ratings, multiple criteria, owner replies, and review moderation.

Pro adds three review modes (Ratings, Testimonials, Endorsements), verified badges, flexible criteria types (thumbs, text), weighted ratings, and a REST API.

[Compare Free vs Pro](https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro/)

== Screenshots ==

1. Member profile header showing star ratings and review count
2. Reviews tab with submitted reviews and criteria breakdown
3. Add Review form with star ratings and anonymous option
4. General Settings - notifications, permissions, moderation
5. Criteria Settings - add and manage rating criteria
6. Display Settings - customize labels and colors
7. Admin Reviews List - manage and moderate all reviews

== Changelog ==

= 3.7.1 =
* Fixed: WordPress Coding Standards compliance across all PHP files
* Fixed: Removed deprecated load_plugin_textdomain call
* Fixed: Plugin Check compatibility improvements

= 3.7.0 =
* New: Owner Replies - profile owners can respond to reviews once
* New: Report System - users can report inappropriate reviews
* New: Auto-hide reviews after configurable report threshold
* New: Admin settings for owner replies, reporting, and auto-hide
* New: Pro Features comparison tab
* Improved: Changed "Flag" to "Report" terminology
* Improved: Admin UI with clearer labels
* Improved: Security hardening
* Fixed: PHP Warning for undefined array key with Pro addon
* Fixed: Template consistency across all modes

= 3.6.0 =
* New: UI improvements for member reviews on directory page
* New: Filter hook for review tab extensibility
* Improved: Script optimization
* Improved: BuddyBoss Theme compatibility
* Fixed: Rating stars visibility with BuddyBoss
* Fixed: Star rating reset on review edit
* Fixed: Email subject and message editing
* Fixed: Text domain loading for translations

= 3.5.0 =
* Added: German (DE) and French (FR) translations
* Updated: Backend labels and strings
* Fixed: Fatal error with BuddyPress Classic
* Fixed: PHPCS compliance issues
* Removed: Unused files for lighter footprint

= 3.4.0 =
* Added: Pending approval notifications for admins
* Added: Meta box for member ratings on edit page
* Added: Conditional script/style loading
* Fixed: String translation issues
* Fixed: BuddyBoss and Youzify compatibility
* Fixed: Notification link and mark-as-read
* Updated: Backend settings UI
* Improved: Performance with minified assets

= 3.3.1 =
* Fixed: Multi-user role issues
* Added: Youzify support
* Added: Member star rating in activity
* Fixed: Review submission with disabled components
* Fixed: Average ratings with single criteria

= 3.3.0 =
* Fixed: Anonymous review avatar and URL
* Fixed: Shortcode form in BuddyBoss
* Fixed: Pagination for reviews and notifications
* Improved: Mobile responsive UI
* Improved: Security enhancements
* Updated: BuddyPress, BuddyBoss, Youzify integration

[View full changelog](https://wordpress.org/plugins/bp-user-profile-reviews/#developers)

== Upgrade Notice ==

= 3.7.0 =
New features: Owner Replies and Report System. Security improvements included. Recommended update for all users.
