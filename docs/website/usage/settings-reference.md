# Settings Reference

Every setting is at **WB Plugins > Member Reviews**. Settings are on three tabs: **General**, **Criteria** and **Display**. The **Overview** and **Discover** tabs have nothing to save.

Each tab saves on its own with its **Save Changes** button. Save before you switch tabs.

The defaults below are what you get on a fresh install, before you change anything.

## General tab

| Setting | What it does | Default |
|---|---|---|
| **Multiple Reviews** | On: a member can review the same person more than once. Off: only once. | On |
| **Show Review Button** | Shows an **Add Review** button in the profile header of other members. The **Add Review** tab under **Reviews** works either way. | Off |
| **Auto Approve Reviews** | On: reviews are published at once. Off: each new review waits for your approval. | On |
| **Member Directory Ratings** | Shows each member's average stars in the members directory and in their profile header. | On |
| **Email Notifications** | Emails the reviewed member about a new review, and emails you when a review needs approval. | On |
| **Email Subject** | Subject line of the email to the reviewed member. | "You Have Received a New Review on [site-name]" |
| **Email Message** | Body of that email. | Friendly pre-written message |
| **Approval Email Subject** | Subject line of the email to you when a review needs approval. Visible only when **Auto Approve Reviews** is off. | "A New Review Requires Your Approval on [site-name]" |
| **Approval Email Message** | Body of that email. Visible only when **Auto Approve Reviews** is off. | Friendly pre-written message |
| **BuddyPress Notifications** | Sends the reviewed member a BuddyPress notification. | On |
| **Update Review** | Lets members edit their own reviews after posting. | On |
| **Reviews Per Page** | How many reviews show on a profile before paging. Minimum 1. A blank or invalid entry becomes 5. | 5 |
| **Reviewer Roles** | Member roles that can write reviews. | All roles |
| **Reviewee Roles** | Member roles that can receive reviews. | All roles |
| **Anonymous Reviews** | Adds a "Send review anonymously." tick box to the review form. | Off |
| **Activity Stream** | Posts each published review to the BuddyPress activity stream. | Off |
| **Owner Replies** | Lets the reviewed member answer each review once. | On |
| **Review Reporting** | Shows a **Report** button so members can flag reviews. | On |
| **Auto-Hide Threshold** | Number of reports that hide a review automatically. 0 means never. Blank or invalid becomes 5. | 5 |

Email placeholders are explained in [Control review emails and notifications](../how-to/control-review-emails-and-notifications.md).

### Which setting does what, in plain words

- Want to read every review first? Switch **Auto Approve Reviews** off.
- Want only some kinds of members to review? Use **Reviewer Roles** and **Reviewee Roles**.
- Worried about abuse? Keep **Review Reporting** on and pick an **Auto-Hide Threshold**.
- Want the community to see reviews as news? Switch **Activity Stream** on.

## Criteria tab

| Setting | What it does | Default |
|---|---|---|
| **Multiple Criteria** | On: members rate each criterion in your list. Off: members give one overall rating. | On |
| Criteria list | Each row is one thing to rate. Use **Add Criteria** to add a row, **Remove** to delete one, drag a row to reorder. | "Member Response" and "Member Skills" |

See [Set up rating criteria](../how-to/set-up-rating-criteria.md) and [Star Ratings and Criteria](../features/star-ratings-and-criteria.md).

## Display tab

| Setting | What it does | Default |
|---|---|---|
| **Review Label** | The singular word used on buttons and messages ("Add Review"). Numbers are not allowed. | Review |
| **Reviews Label (Plural)** | The plural word. It names the profile tab and sets its web address. | Reviews |
| **Rating Color** | The color of the stars. Pick a color or enter a code. | #FFC400 (yellow) |

Under these settings, the **Top Members Shortcode** box shows the shortcode and its options, for easy copying. See [Shortcodes](../shortcodes/shortcodes.md).

## Overview and Discover tabs

- **Overview** shows review counts and which features are on. It has links to the settings. It saves nothing.
- **Discover** lists other free Wbcom Designs plugins. It saves nothing.

## Roles needed

Only administrators (anyone who can manage site options) can open these settings. See [Admin Screen Tour](../getting-started/admin-screen-tour.md).
