# Moderation and Reporting

You have three tools to keep reviews trustworthy: an approval step, member reporting, and automatic hiding. All three are set on **WB Plugins > Member Reviews > General**.

## Approval: go live at once, or wait for you

The **Auto Approve Reviews** switch decides what happens to a new review.

| Setting | What happens |
|---|---|
| On (the default) | The review is published the moment it is submitted. |
| Off | The review waits as a draft. Nobody but its writer and your moderators can see it, and it does not count in anyone's rating until you approve it. |

When approval is needed:

- A number badge shows next to **Reviews** in the admin menu.
- The **Overview** tab shows the count under **Pending Approval**.
- You get an email if **Email Notifications** is on.
- The member sees "Thank you for sharing your Review! After admin approval, it will be displayed on members' profiles."

Step by step: [Require approval before reviews go live](../how-to/require-approval-for-reviews.md).

## Reporting: members flag what they find wrong

When **Review Reporting** is on (the default), any logged-in member sees a **Report** button under reviews written by other people. They do not see it under their own review.

1. The member clicks **Report**.
2. A box opens asking them to pick a reason: "Spam or fake review", "Harassment or abuse", "Inappropriate content" or "Conflict of interest".
3. They click **Submit Report**.

A member can report a review only once. After that, they see "Reported" in place of the button.

The plugin does not email you when a review is reported. Check the **Overview** tab (a warning appears at the top) or open **Reviews** in the admin and click the **Reported** link above the list.

## Auto-hide: enough reports hides the review

**Auto-Hide Threshold** is the number of reports that hides a review automatically. The default is 5.

| Value | Effect |
|---|---|
| A number such as 3 | After 3 reports, the review is hidden from the profile and set back to draft. |
| 0 | Reports never hide a review. You decide on every report yourself. |
| Left blank | The plugin uses 5. A blank box does not turn auto-hide off. |

A hidden review is not deleted. It disappears from the profile, the member's rating and count, the activity stream and its own direct link. You can see it in the admin with an **Auto-hidden** badge and the date it was hidden.

Suggested thresholds: 2 or 3 for small communities, about 5 for medium ones, 10 or more for large ones where a few angry members could otherwise hide good reviews.

## Dealing with a report

You have two choices on a reported review. They do different things.

| Action | What it means | Effect on the review |
|---|---|---|
| **Dismiss Reports** | "These reports were wrong." | If the plugin had hidden the review, it is published again. The reports so far stop counting toward the threshold, so only new reports can hide it again. |
| **Mark Resolved** | "I have dealt with this." | The report is marked resolved. A hidden review stays hidden. If you want it live, publish it yourself. The reports so far stop counting toward the threshold, the same as Dismiss. |

If a review really is inappropriate, edit or trash it. See [Handle a reported review](../how-to/handle-a-reported-review.md).

## Who can moderate

Approving reviews, dismissing reports and resolving reports need the WordPress Editor role or higher (the `edit_others_posts` capability). Contributors and Authors cannot do these things.

## Visibility rules for unpublished reviews

- A pending review or a review hidden by reports does not show on the profile.
- If someone opens its direct link, only the review's writer and your moderators can see it. Everyone else sees "Review not found."
- A review is only visible on the profile of the member it was written about.

## Limiting who can take part

Use **Reviewer Roles** and **Reviewee Roles** on the General tab to restrict who can write reviews and who can receive them. See [Choose who can write and who can receive reviews](../how-to/choose-who-can-review-and-be-reviewed.md).
