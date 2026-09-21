# Frequently Asked Questions

Common questions about BuddyPress Member Reviews. Settings referenced here live at **WB Plugins > Member Reviews**.

## Is BuddyPress required?

Yes. The plugin needs BuddyPress 6.0 or higher, or BuddyBoss Platform 1.8 or higher, with the Members component active. Reviews live on member profiles, so there is no standalone mode.

## How do members submit reviews?

A member opens another member's profile, clicks the Reviews tab, then uses the Add Review button. If directory ratings are enabled, they can also start a review from the members directory. "Reviews" is just the default label; renaming the plural label under Display settings changes the tab and its URL slug.

## Can members review themselves?

No. Self-reviews are blocked so scores stay honest.

## How do I add custom rating criteria?

Open the **Criteria** tab. Two criteria ship as defaults, "Member Response" and "Member Skills". Add, rename, or remove them freely, for example "Communication" or "Reliability".

## Can I change the 5-star scale?

No. The scale is fixed at 5 stars, which reviewers instantly understand. It cannot be changed by setting or filter.

## Can I require approval before reviews appear?

Yes, but the default is the other way round. **Auto Approve Reviews** is on by default, so reviews publish immediately. To review every submission first, turn Auto Approve off on the General tab. New reviews then stay as drafts until you approve them from the Reviews screen.

## Can members edit a review after posting it?

Yes, when the **Update Review** setting is on (on by default). Members can only edit their own reviews. Members cannot delete reviews; only administrators can.

## Can I control who reviews whom?

Yes. The General tab has two role selectors: **Reviewer Roles** for who may leave reviews, and **Reviewee Roles** for whose members can be reviewed.

## Do reviews post to the activity stream?

They can. Enable **Activity Stream** on the General tab and each new review publishes to the BuddyPress activity stream. It is off by default.

## How do notifications work?

Members get a BuddyPress notification when they receive a review, and an email if email notifications are enabled. Admins get a pending-approval alert when moderation is on. The BuddyPress notification and the email are separate switches, so you can run one without the other.

## What happens when a review is reported?

Reported reviews are flagged in the admin. Filter the Reviews list to the Reported view, open a review to read its Report Details meta box, then dismiss or resolve the report, individually or in bulk. Reviews auto-hide once they pass the report threshold you set (default 5).

## Does it work with BuddyBoss?

Yes. BuddyBoss Platform 1.8 and higher is supported, and the plugin is tested with the BuddyBoss Theme.

## Does it work with Multisite?

Yes. Activate it network-wide or per site. Settings are stored per site.

## How do I display top-rated members?

Two ways. Add the **BP Member Review Widget** for a top-rated or most-reviewed list, or use the `[bupr_display_top_members]` shortcode on any page or post. There is also a **BP Displayed Member Rating Widget** that shows the rating of the member whose profile is being viewed. On a block theme, add either widget through the Legacy Widget block.

## What attributes does the shortcode accept?

`[bupr_display_top_members]` accepts four: `title` (heading, default empty), `total_member` (count, default 5), `type` (`top rated` by aggregate rating or `top reviewed` by review count, default `top rated`), and `avatar` (`show` or `hide`, default `show`). For example: `[bupr_display_top_members title="Most Reviewed" type="top reviewed" total_member="10" avatar="hide"]`.

## Can members reply to reviews about them?

Yes. Profile owners can reply once to each review. It is controlled by **Owner Replies** on the General tab and is on by default.

## How do I change the Reviews tab name or star color?

On the **Display** tab, set the singular and plural review labels and the star color. The plural label also drives the tab name and slug. You can also change the tab label in code with the `bupr_review_tab_name` filter.

## Is the plugin translation ready?

Yes. German and French translations ship with the plugin, more locales are being added, and a POT file (`bp-member-reviews.pot`) is included so you can translate into any language. The plugin is WPML compatible and includes RTL support.

## What are the requirements?

WordPress 6.5 or higher, PHP 8.0 or higher, and BuddyPress 6.0+ or BuddyBoss Platform 1.8+ with the Members component active.

## Where can I get support?

See the [documentation](https://docs.wbcomdesigns.com/) and [Wbcom Designs support](https://wbcomdesigns.com/support/).
