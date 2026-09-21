# Managing Reviews in the Admin

Every review is stored as a `review` custom post, so you manage reviews from a standard WordPress list screen with a few review-specific additions.

## Where reviews live

Go to **Reviews > All Reviews** in the WordPress admin. This screen is separate from the settings screen at **WB Plugins > Member Reviews**.

## List columns

- **Title**: the review title or the first line of its content.
- **Reports**: flag count and status when a review has been reported.
- **Author**: the member who wrote the review.
- **Date**: when it was submitted.

## Statuses

Reviews use standard WordPress post statuses:

- **Draft**: pending approval or auto-hidden. Not visible on profiles. This is the state new reviews land in when Auto Approve is off, and the state a review returns to when it passes the report threshold.
- **Published**: approved and visible on the profile, counted in the aggregate rating, and posted to the activity stream if that is enabled.
- **Trash**: removed from the site, recoverable until permanently deleted.

When Auto Approve is off, a count badge next to the Reviews menu shows how many drafts await approval.

## Filtering

Use the status views above the list (All, Published, Draft, Trash) and the **Reported** view, which shows only flagged reviews with a count badge. You can also filter by month.

## Approving reviews

- Single: hover the review title and use the row action to approve, or open Quick Edit and set the status to Published.
- Bulk: select several reviews, choose the bulk edit action, set status to Published, and apply.

## Editing reviews

Open a review to edit its content and its per-criterion star ratings. The star values are set in the **Member Ratings** meta box on the edit screen. You cannot change the review author.

## Handling reports

Open a reported review and use its **Report Details** meta box, which lists each report with the reporter, reason, and date. From there:

- **Dismiss** clears the reports and keeps the review visible.
- **Resolve** marks the reports resolved.

Dismiss and Resolve are also available as bulk actions on the Reviews list. If a review is genuinely inappropriate, edit its content or move it to Trash.

### Auto-hidden reviews

A review that reaches the **Review Flag Threshold** (set on the General tab, default 5) is set back to draft automatically and leaves the profile. To restore it, review the content and set the status back to Published, then dismiss the reports.

## Owner replies

When **Enable Review Owner Reply** is on, profile owners can reply once to each review; replies show beneath the review on the front end. See [Owner Replies](../features/owner-replies.md).

## Aggregate ratings

A member's aggregate rating and review count recalculate automatically when a review is approved, edited, trashed, or changes status. A daily background job also reconciles the cached values, so no manual recalculation tool is needed.
