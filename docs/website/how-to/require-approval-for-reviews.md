# Require Approval Before Reviews Go Live

Goal: make every new review wait until you have read it and approved it.

## Turn approval on

1. Go to **WB Plugins > Member Reviews** and open the **General** tab.
2. Switch **Auto Approve Reviews** **off**. ("Automatically approve reviews without requiring manual approval.")
3. Two new fields appear: **Approval Email Subject** and **Approval Email Message**. This is the email you will receive for each new review. Edit the wording if you like.
4. Check that **Email Notifications** is on, so the email is actually sent.
5. Click **Save Changes**.

From now on, new reviews are saved as drafts. Nobody sees them on profiles and they do not change anyone's rating.

## Approve or reject a pending review

1. Go to **Reviews** in the admin menu. A number badge next to the menu name tells you how many are waiting.
2. Find the review. Click the **Draft** link above the list to see only the waiting ones.
3. Hover over the review title. Click **View Review** to read it on the site, or open the review to read it here.
4. To accept it, click **Approve** under the title. The page reloads and the review is now published.
5. To reject it, click **Trash** under the title.

You can also open the review and click **Publish** in the usual WordPress box.

Only Administrators and Editors can approve. Contributors and Authors cannot.

## What members see

- After submitting, the writer sees: "Thank you for sharing your Review! After admin approval, it will be displayed on members' profiles."
- When you approve, the reviewed member gets their usual email and BuddyPress notification, and the review appears on their profile.
- If **Activity Stream** is on, the review is posted to the activity stream at that moment.

## Tips

- The "needs approval" email goes to the administrator email set in **Settings > General**. It links to the Reviews list.
- The **Overview** tab shows a **Pending Approval** count. Reviews hidden by reports are counted separately.
- Switching approval back on later does not touch reviews that are already published.
- Switching **Auto Approve Reviews** back on does not publish reviews that are already waiting. Approve those by hand.
- A review that was hidden because of reports looks like a draft too. See [Handle a reported review](handle-a-reported-review.md).

## If it does not work

- You do not get the approval email: see "Emails are not arriving" in [Troubleshooting](../troubleshooting/troubleshooting.md).
- The **Approve** link is missing: it only shows on draft reviews, for Administrators and Editors.
