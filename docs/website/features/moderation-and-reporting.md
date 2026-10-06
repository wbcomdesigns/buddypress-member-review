# Moderation and Reporting

Keep reviews trustworthy with approval control, member reporting, and an auto-hide threshold. All settings live on the **General** tab under **WB Plugins > Member Reviews**.

## Approval mode

**Auto Approve Reviews** is on by default, so new reviews publish immediately. Turn it off to hold every new review as a draft until an admin approves it from the Reviews screen.

When auto-approve is off, admins receive a pending-approval email alert (if email notifications are on), and a count badge appears next to the Reviews admin menu showing how many reviews await approval.

## Member reporting

When **Enable Review Flag/Report** is on (default), any logged-in member can report a review they find inappropriate.

1. The member clicks **Report** below the review.
2. A dialog opens to pick a reason and submit.
3. The report is recorded and the review's report count increments.

Each member can report a given review once.

### Report reasons

The report dialog offers four fixed reasons:

- Spam or fake review
- Harassment or abuse
- Inappropriate content
- Conflict of interest

## Auto-hide threshold

**Review Flag Threshold** (default 5) sets how many reports auto-hide a review. When a review reaches the threshold it is set back to draft and disappears from the profile, but stays in the admin for review. Set the threshold to 0 to disable auto-hide.

Suggested thresholds:

- Small communities: 2 to 3 reports.
- Medium communities: 5 to 7 reports.
- Large communities: 10 or more, to avoid false positives.

## Handling reports in the admin

Open a reported review in the admin and use its **Report Details** meta box:

- **Dismiss** clears the reports and keeps the review as it is.
- **Resolve** marks the reports resolved.

You can also filter the Reviews list to the Reported view and act on reports in bulk. See [Managing Reviews in the Admin](../usage/managing-reviews.md).

## Role gating

The General tab has two role selectors: one for the roles allowed to leave reviews, and one for the roles whose members can be reviewed. Use these to restrict who participates.
