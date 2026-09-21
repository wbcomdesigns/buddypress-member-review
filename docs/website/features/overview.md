# Features Overview

BuddyPress Member Reviews gives your community a reputation layer. This page summarizes what the free plugin does. Every capability here is controlled from **WB Plugins > Member Reviews**.

## Reviews and ratings on member profiles

- A Reviews tab on every member profile, with per-criteria star ratings and an aggregate score.
- Rate members against criteria you define. Two are seeded on activation, "Member Response" and "Member Skills", and you can rename, remove, or add your own.
- Members can edit their own reviews after posting when the **Allow update** setting is on (on by default).
- Profile owners can reply to reviews left about them, once per review.
- Self-reviews are blocked, so scores stay honest.
- Optional anonymous reviews for members who want to give feedback privately.

See [Star Ratings and Criteria](star-ratings-and-criteria.md).

## Ratings on the members directory

- Show each member's star rating on the members directory (on by default).
- An Add Review button on directory entries so members can review without opening the profile first (on by default).
- Both are individually switchable on the General tab.

## Moderation you control

- **Auto Approve Reviews** is on by default, so reviews go live immediately. Turn it off to hold every new review as a draft until an admin approves it.
- Members can report inappropriate reviews.
- Reviews auto-hide once they pass a report threshold you set (default 5).
- A Report Details meta box on each review in the admin, with dismiss and resolve actions, plus a flagged-reviews filter and bulk actions on the Reviews list.
- Reviewer and reviewee role gating: choose which roles may leave reviews and which roles may receive them.

See [Moderation and Reporting](moderation-and-reporting.md).

## Notifications

- BuddyPress notifications when a member receives a review.
- Email notifications for new reviews, and pending-approval alerts for admins.
- Both can be switched off independently on the General tab.

## Activity stream

- Publish reviews to the BuddyPress activity stream so the community sees new feedback as it happens. Off by default; switch it on in General settings.

## Display anywhere

- Two widgets: **BP Member Review Widget** (top rated or most reviewed members) and **BP Displayed Member Rating Widget** (the rating of the member whose profile is being viewed).
- The `[bupr_display_top_members]` shortcode for a top-rated or top-reviewed list on any page or post.
- Customize the review labels and star color, including the singular and plural label used for the profile tab.

See [Shortcode](../shortcodes/shortcodes.md) and [Widgets](../shortcodes/widgets.md).

## Owner replies

Profile owners can respond once to each review left about them. See [Owner Replies](owner-replies.md).
