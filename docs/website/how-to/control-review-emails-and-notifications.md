# Control Review Emails and Notifications

Goal: choose who is told about reviews, and change the wording of the emails.

## Switch emails and notifications on or off

1. Go to **WB Plugins > Member Reviews** and open the **General** tab.
2. Set **Email Notifications**. ("Send an email notification to members when someone reviews their profile.") On by default. It also controls the "needs approval" email you receive.
3. Set **BuddyPress Notifications**. ("Notify members through BuddyPress when they receive a new review.") On by default.
4. Click **Save Changes**.

The two switches are separate. You can have one without the other.

## Change what the emails say

On the same tab:

1. Edit **Email Subject** and **Email Message**. This is the email the reviewed member gets.
2. If **Auto Approve Reviews** is off, also edit **Approval Email Subject** and **Approval Email Message**. This is the email you get when a review needs approval.
3. Click **Save Changes**.

Use these placeholders. The plugin swaps them for real values when it sends the email.

| Placeholder | Becomes | Use in |
|---|---|---|
| `[site-name]` | Your site name | Both subjects and both messages |
| `[user-name]` | The reviewed member's name | Member email message |
| `[reviewer-name]` | The reviewer's name ("An anonymous user" if anonymous) | Member email message |
| `[review-link]` | A link to the review | Member email message |
| `[site-admin]` | The administrator's name | Approval email message |
| `[review-aproval-link]` | A link to the Reviews list in the admin (spelled with one "p") | Approval email message |

## What members see

- When a review is published, the reviewed member gets this email and a notification bubble in BuddyPress. The bubble reads "Alex posted a review".
- Emails are sent as formatted text, so paragraphs and links in the message box are kept.

## Where the approval email goes

It goes to the **Administration Email Address** under **Settings > General** in WordPress. To change who receives it, change that address.

## Tips

- Keep the placeholders in square brackets exactly as written, or they will show up as plain text in the email.
- Test with a second account, because emails depend on your hosting. A mail plugin (SMTP) makes delivery much more reliable.
- Reported reviews and owner replies do not send any email. See [Notifications and Activity](../features/notifications-and-activity.md).
- Approving a pending review sends the reviewed member their email and notification at that moment.

## If it does not work

See "Emails are not arriving" in [Troubleshooting](../troubleshooting/troubleshooting.md).
