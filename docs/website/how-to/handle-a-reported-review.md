# Handle a Reported Review

Goal: find reviews that members have reported, decide what to do, and set how many reports it takes to hide a review.

## Check the report settings

1. Go to **WB Plugins > Member Reviews** and open the **General** tab.
2. Make sure **Review Reporting** is on. ("Allow users to report inappropriate reviews for admin moderation.")
3. Set **Auto-Hide Threshold**. This is how many reports hide a review automatically. The default is 5. Enter 0 so that reports never hide a review.
4. Click **Save Changes**.

If you leave **Auto-Hide Threshold** blank, the plugin uses 5. It does not switch auto-hide off.

## Find reported reviews

The plugin does not email you about reports. Check one of these:

- The **Overview** tab at **WB Plugins > Member Reviews**. A warning at the top says how many reviews are reported. Click **Review now**.
- The **Reviews** list. Click **Reported** above the list to show reviews with open reports. Click **Auto-hidden** to show only reviews that were hidden by reports.

In the list, the **Reports** column shows how many reports a review has. An auto-hidden review also shows an **Auto-hidden** badge. Hover over it to see when it was hidden.

## Read the reports

1. Open the review (click its title).
2. Look at the **Report Details** box on the right. It shows the status, the total number of reports, and for each report the reason, who reported it and when.

## Decide

| If the reports are... | Do this |
|---|---|
| Wrong, and the review is fine | Click **Dismiss Reports**. A review that was hidden by reports is published again. |
| Right, and the review should go | Click **Trash** (or edit the text first if you only want to remove part of it). Then click **Mark Resolved** if you want to keep a record. |
| Right, but you want to keep the review hidden | Click **Mark Resolved**. The review stays hidden. |
| Right, but you want it back on the profile | Click **Mark Resolved**, then publish the review. The reports you resolved no longer count toward hiding it again. |

After you click, the page shows "Review reports have been dismissed." or "Review reports have been marked as resolved."

Only Administrators and Editors can do this.

## Do several at once

1. In the **Reviews** list, tick the reviews.
2. Pick **Dismiss Reports** or **Resolve Reports** under **Bulk actions** and click **Apply**.
3. The page shows how many reviews were updated.

**Dismiss Reports** in bulk works exactly like on a single review: a review hidden by reports is published again and its report count is reset. **Resolve Reports** marks the reports as resolved and leaves a hidden review hidden.

## What happens with the next report

Both **Dismiss Reports** and **Mark Resolved** close the reports you have looked at. They no longer count toward the threshold. If the threshold is 5, the review needs 5 new reports to be hidden again.

The difference is only what happens to a hidden review: Dismiss publishes it again, Mark Resolved leaves it hidden until you publish it.

## What members see

- A member who reported a review sees "Reported" in place of the **Report** button.
- A hidden review disappears from the profile, the member's rating, and the activity stream.
- The member who reported is not told what you decided.

## How you hear about reports

When a member reports a review, the site administrator gets a BuddyPress notification: "A member reported a review. Check it and dismiss or resolve the report." Clicking it opens the review in the admin. It does not name the member who reported. It follows the **BuddyPress Notifications** switch, and there is one unread notification per review, however many reports arrive.

## Tips

- Start with a threshold of 3 on a small site and raise it as you grow.
- A single report never hides a review unless the threshold is 1.
- Members cannot report their own reviews.

## If it does not work

See "A reported review has vanished" in [Troubleshooting](../troubleshooting/troubleshooting.md).
