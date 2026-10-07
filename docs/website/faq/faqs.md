# Frequently Asked Questions

Short answers to the questions site owners ask most. Settings are at **WB Plugins > Member Reviews**.

## Setup and basics

### Do I need BuddyPress?

Yes. You need BuddyPress 6.0 or higher, or BuddyBoss Platform 1.8 or higher, with the **Members** component on. You also need WordPress 6.5 or higher and PHP 8.1 or higher.

### Where are the settings?

At **WB Plugins > Member Reviews**. The list of reviews is a separate menu called **Reviews**. See [Admin Screen Tour](../getting-started/admin-screen-tour.md).

### How do members write a review?

They open another member's profile, click the **Reviews** tab, then **Add Review**. If you switched on **Show Review Button**, there is also an **Add Review** button in the profile header. See [Submitting and Editing Reviews](../usage/submitting-reviews.md).

### Why can't I see the Add Review tab on my own profile?

By design. Members cannot review themselves, so version 3.8.1 hides the tab on your own profile. To review someone, open their profile. If you also cannot see it on other profiles, see "I cannot see Add Review" in [Troubleshooting](../troubleshooting/troubleshooting.md).

### Why is there no Add Review button in the profile header?

**Show Review Button** is off by default. Switch it on at **WB Plugins > Member Reviews > General**. The **Add Review** tab under **Reviews** works either way.

### Can I rename "Review" to something else?

Yes, for example "Testimonial". Change **Review Label** and **Reviews Label (Plural)** on the **Display** tab. The plural word also changes the web address of the profile tab. See [Change the wording and star color](../how-to/change-labels-and-star-color.md).

## Ratings

### How is the average rating calculated?

The stars in each review are averaged. Then the review scores are averaged. Every published review counts equally. Reviews with no stars are counted in the total but not in the average. Hidden, pending and trashed reviews are left out. See [Star Ratings and Criteria](../features/star-ratings-and-criteria.md).

### Can I change the 5-star scale?

No. It is always 1 to 5 stars.

### What happens to old reviews when I rename or remove a criterion?

They keep their stars under the name they were written with. Nothing is lost, and they still count in the average. See [Set up rating criteria](../how-to/set-up-rating-criteria.md).

### Can I use one overall rating instead of several criteria?

Yes. Switch **Multiple Criteria** off on the **Criteria** tab, and keep at least one row in the list.

## Who can do what

### Can members edit their reviews?

Yes, when **Update Review** is on (the default). They click **Edit Review** under their own review. Editing does not publish a review that is still waiting for approval.

### Can members delete their reviews?

No. There is no delete button on the front end. Members can ask you to remove a review.

### Who can delete a review?

Administrators and Editors, from the **Reviews** list in the admin (**Trash**, then **Delete Permanently**). Deleting a review updates the member's average and count.

### Can I limit who reviews whom?

Yes. Use **Reviewer Roles** and **Reviewee Roles** on the **General** tab. See [Choose who can write and who can receive reviews](../how-to/choose-who-can-review-and-be-reviewed.md).

### Can a member review the same person twice?

By default, yes (**Multiple Reviews** is on). Switch it off to allow one review per person.

### Can I hold reviews for approval?

Yes. Switch **Auto Approve Reviews** off. See [Require approval before reviews go live](../how-to/require-approval-for-reviews.md).

### Can reviews be anonymous?

Yes, if you switch on **Anonymous Reviews**. The name and picture are hidden from members, but you still see the writer in the admin. See [Turn on anonymous reviews](../how-to/turn-on-anonymous-reviews.md).

## Moderation

### What happens when a review is reported?

The review gets a report count. When the count reaches **Auto-Hide Threshold** (5 by default) the review is hidden until you look at it. You can **Dismiss Reports** (the review comes back) or **Mark Resolved**. See [Handle a reported review](../how-to/handle-a-reported-review.md).

### Do I get an email when a review is reported?

No. Check the **Overview** tab or the **Reported** link above the **Reviews** list.

### Can I stop a review from being hidden automatically?

Yes. Set **Auto-Hide Threshold** to 0. Leaving the box blank does not do this. A blank box means 5.

## Display and other plugins

### Do reviews appear in the members directory?

Yes, as stars beside each member who has reviews, when **Member Directory Ratings** is on (the default). The same setting controls the rating in the profile header.

### How do I show top-rated members?

Use the `[bupr_display_top_members]` shortcode, or the **BP Member Review Widget**. See [Show top-rated members on a page](../how-to/show-top-rated-members.md).

### Do reviews show in the activity stream?

Only if you switch on **Activity Stream**. It is off by default.

### Does it work with BuddyBoss?

Yes. The plugin has its own handling for BuddyBoss Platform 1.8 or higher, including the BuddyBoss members directory. It is also written for the BuddyPress Nouveau and Legacy templates and works with Youzify profile headers.

### Does it work with the BuddyX and Reign themes?

Yes. Dark mode in those themes is supported. The plugin follows the theme's own dark setting, not the visitor's device setting.

### Can members reply to reviews about them?

Yes. They can reply once to each review. See [Let members reply to reviews](../how-to/let-members-reply-to-reviews.md). The reviewer is not told about the reply.

## Data and languages

### What happens to reviews if I deactivate or delete the plugin?

Nothing is deleted. Reviews, ratings and settings stay in your database, but the **Reviews** menu and the profile tab disappear. Activate the plugin again and everything returns. The daily background job is removed on deactivation and set up again on activation.

### Which languages are included?

German, French, Spanish, Italian and Portuguese (Brazil). The plugin is ready for other languages with the included translation template, and supports right-to-left languages.

## Help

### Where do I get support?

See the [documentation](https://docs.wbcomdesigns.com/) and [Wbcom Designs support](https://wbcomdesigns.com/support/).
