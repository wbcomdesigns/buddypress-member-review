# Managing Reviews in Admin

Learn how to approve, edit, and moderate member reviews from your WordPress admin panel.

## Accessing Reviews

Go to **Reviews > All Reviews** in your WordPress admin menu. This displays all reviews submitted on your site.

![Admin Reviews List](../images/admin-reviews-list.png)

## Reviews List Columns

The reviews list displays these columns:

| Column | Description |
|--------|-------------|
| **Title** | Review title or first line of content |
| **Reports** | Flag count and status label (e.g., "3 flags - Flagged") |
| **Author** | Member who wrote the review |
| **Date** | When the review was submitted |

## Review Statuses

Reviews use standard WordPress post statuses:

**Draft (Pending Approval)**
- Awaiting admin approval
- Not visible on member profiles
- Displayed when **Auto Approve** is disabled

**Published (Approved)**
- Visible on member profiles
- Appears in activity stream (if enabled)
- Counts toward member ratings
- Default status when auto-approve is enabled

**Trash**
- Moved to trash (recoverable for 30 days)
- Not visible anywhere on site
- Permanently deleted after 30 days
- Can be restored before permanent deletion

## Filtering Reviews

Use the filter options at the top of the list:

**By Status (view tabs):**
- All (default)
- Published
- Draft
- Trash
- **Reported** - A dedicated view tab that shows only flagged reviews, with a count badge showing the number of reported reviews

**By Date:**
- All dates (default)
- Specific month and year

**Draft Count Badge:**

When **Auto Approve** is disabled, a count badge appears next to the **Reviews** admin menu item showing the number of pending (draft) reviews. This provides at-a-glance visibility into how many reviews are awaiting approval.

## Approving Reviews

### Single Review Approval

1. Locate the pending review in the list
2. Hover over the review title
3. Click **Approve** in the action links
4. The review status changes to Approved

**Quick Edit Method:**

1. Click **Quick Edit** under the review title
2. Change **Status** to **Published**
3. Click **Update**

### Bulk Approval

Approve multiple reviews at once:

1. Check the boxes next to reviews you want to approve
2. Select **Edit** from the **Bulk Actions** dropdown
3. Click **Apply**
4. In the bulk edit panel, change **Status** to **Published**
5. Click **Update**

## Editing Reviews

### Full Edit

1. Click the review title or **Edit** link
2. Modify the review content, rating, or other fields
3. Change status if needed
4. Click **Update**

**Editable Fields:**

- Review content
- Star rating
- Criteria ratings (if multiple criteria enabled)
- Status (Draft, Published)
- Reviewed member (reassign to different member)
- Date submitted

**Note:** You cannot change the review author from the edit screen.

### Quick Edit

For faster changes:

1. Hover over the review title
2. Click **Quick Edit**
3. Modify available fields (status, reviewed member, rating)
4. Click **Update**

## Handling Reported Reviews

When members flag reviews as inappropriate, they appear with a report indicator.

### Reviewing Reported Reviews

**Report Indicator:**

Reviews with reports show a red flag icon and number in the **Reports** column.

**Viewing Reports:**

1. Click the review title to edit
2. Scroll to the **Reports** meta box
3. View all reports with:
   - Reporter name
   - Report reason
   - Report date

### Report Actions

**1. Dismiss Reports (Keep Review Visible)**

If the review is acceptable:
1. Keep status as **Approved**
2. Click **Dismiss All Reports** in the Reports meta box
3. The review remains visible
4. Report count resets to zero

**2. Revert Review to Draft**

If the review needs investigation:
1. Change status to **Draft**
2. The review disappears from member profiles
3. Reports remain visible in admin
4. You can publish it again later

**3. Delete Review**

If the review violates guidelines:
1. Click **Move to Trash**
2. The review is removed from profiles
3. Recoverable from Trash for 30 days
4. Permanently deleted after 30 days

**4. Edit Review Content**

If the review has minor issues:
1. Edit the review content directly
2. Remove offensive language or inappropriate content
3. Keep status as **Approved**
4. Optionally dismiss reports

### Auto-Hide Threshold

Reviews automatically hide when they reach the **Flag Threshold** set in **Settings > BP Member Review > General**.

**Default:** 5 flags

**What Happens:**
- Review status changes to **Draft** automatically
- Review disappears from member profile
- Review remains in admin for review

**To Restore:**
1. Review the flagged content
2. If acceptable, change status to **Published**
3. Dismiss reports
4. The review reappears on the profile

## Managing Owner Replies

When **Enable Review Owner Reply** is enabled in settings, profile owners can respond to reviews. Admins can view, edit, or delete these replies from the admin panel. See [Owner Replies](../owner-replies/owner-replies.md) for complete documentation on this feature.

## Bulk Actions

Perform actions on multiple reviews at once:

### Available Bulk Actions

**Edit**
- Change status for multiple reviews
- Modify dates

**Move to Trash**
- Delete multiple reviews at once
- Recoverable for 30 days

**Dismiss Reports**
- Dismiss all reports on selected reviews
- Resets flag count and status to "none"
- Reviews remain published and visible

**Resolve Reports**
- Mark reports on selected reviews as resolved
- Sets flag status to "resolved"
- Reviews remain in their current status

**Restore** (when viewing Trash)
- Restore multiple trashed reviews
- Returns reviews to previous status

**Delete Permanently** (when viewing Trash)
- Permanently delete multiple reviews
- Cannot be undone

### Using Bulk Actions

1. Check boxes next to reviews you want to modify
2. Select action from **Bulk Actions** dropdown
3. Click **Apply**
4. Confirm if prompted

**Pro Tip:** Use filters to narrow down reviews before bulk actions (e.g., filter by "Pending" status, then bulk approve).

## Review Data Fields

Understanding all review data stored in the database:

**Core Fields:**
- Review content (text)
- Overall star rating (1-5)
- Reviewed member ID
- Review author ID
- Submission date
- Status (draft, published)

**Multi-Criteria Fields (if enabled):**
- Individual criterion ratings
- Criterion names
- Average rating calculation

**Metadata:**
- Anonymous flag (if submitted anonymously)
- Activity post ID (if posted to activity stream)
- Report count and data
- Owner reply content and date
- Last modified date

## Aggregate Ratings

Member aggregate ratings (overall ratings shown on profiles) update automatically when:

- A review is approved
- A review rating is changed
- A review is deleted or trashed
- A review status changes

### Rating Recalculation

Aggregate ratings recalculate automatically whenever reviews are approved, edited, deleted, or have their status changed. No manual recalculation tool is needed.

## Best Practices

### Moderation Guidelines

**Respond Quickly:**
- Review pending submissions within 24-48 hours
- Address reported reviews promptly
- Notify members if their review is rejected

**Be Consistent:**
- Apply the same standards to all reviews
- Document your moderation policy
- Train multiple moderators on guidelines

**Communicate Clearly:**
- Explain why reviews are rejected (if you contact the reviewer)
- Create public review guidelines
- Set clear expectations for appropriate content

### Preventing Spam

**Enable Approval:**
- Disable **Auto Approve** for tighter control
- Review submissions before they go live
- Watch for patterns in spam reviews

**Monitor Reports:**
- Check flagged reviews regularly
- Look for repeat offenders
- Block or ban users who repeatedly violate guidelines

**Use Role Restrictions:**
- Exclude problematic roles from reviewing
- Protect administrator profiles from reviews
- Limit new user review permissions

### Handling Disputes

**Investigate Thoroughly:**
- Read both the review and any replies
- Check the reviewer's history
- Look for context in member interactions

**Stay Neutral:**
- Don't take sides in personal disputes
- Focus on guideline violations, not opinions
- Allow negative reviews if they're honest and appropriate

**Document Decisions:**
- Keep notes on why reviews were removed
- Track repeat issues with specific members
- Use notes field in user profiles

**Communication:**
- Email members privately about removed reviews
- Provide clear reasons for moderation actions
- Offer appeal process for disputed decisions

## Troubleshooting

**Reviews not appearing after approval**

Check that:
- Review status is set to **Approved**
- The reviewed member still exists
- Cache is cleared (if using caching plugin)
- Theme is BuddyPress compatible

**Rating count doesn't match review count**

This can happen when:
- Some reviews are in Draft (pending) status
- Reviews are in Trash

Aggregate ratings recalculate automatically when review statuses change. If counts still seem off, check that no reviews are stuck in Draft or Trash status.

**Can't edit or delete reviews**

Verify:
- You have Administrator or Editor role
- The review exists in the database
- No JavaScript errors in browser console
- WordPress and plugin are up to date

**Reports not showing**

Check that:
- **Enable Review Flag/Report** is enabled in settings
- Members are logged in (only logged-in members can flag)
- The review is in Published status (can only flag visible reviews)

## Related Documentation

- [Admin Settings Reference](admin-settings.md) - Configure review approval and moderation
- [Pro Features Overview](pro-features.md) - Review modes, verified badges, and more
- [Shortcodes](../shortcodes-widgets/shortcodes.md) - Display approved reviews
- [Widgets](../shortcodes-widgets/widgets.md) - Show reviews in sidebars
