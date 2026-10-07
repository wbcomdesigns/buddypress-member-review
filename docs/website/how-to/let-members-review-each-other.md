# Let Members Review Each Other

Goal: have members writing and reading reviews on your site in about five minutes.

Reviews work out of the box. This guide checks the few settings that matter, then tests the whole thing with two accounts.

## Before you start

- BuddyPress (or BuddyBoss) is active and the **Members** component is on.
- You have two member accounts to test with (call them Alex and Sam). Neither needs to be an administrator.

## Steps

1. Go to **WB Plugins > Member Reviews** and open the **General** tab.
2. Decide on approval. **Auto Approve Reviews** is on by default, so reviews appear at once. To read each review first, switch it off. See [Require approval before reviews go live](require-approval-for-reviews.md).
3. Check **Multiple Reviews**. On (the default) means a member can review the same person more than once. Off means once only.
4. Decide if you want an **Add Review** button in the profile header. Switch **Show Review Button** on if you do. It is off by default. Without it, members still get **Add Review** under the profile's **Reviews** tab.
5. Check **Reviewer Roles** and **Reviewee Roles**. With nothing chosen, every role can review and be reviewed. See [Choose who can write and who can receive reviews](choose-who-can-review-and-be-reviewed.md).
6. Click **Save Changes**.
7. Open the **Criteria** tab. Rename or add what members rate (see [Set up rating criteria](set-up-rating-criteria.md)) and click **Save Changes**.

## Test it

1. Log in as Alex and open Sam's profile.
2. Click the **Reviews** tab. Under it you will see **Add Review**. (If you switched on **Show Review Button**, there is also an **Add Review** button in the profile header.)
3. Click **Add Review**, rate each criterion by clicking the stars, and write a few words.
4. Click **Submit Review**.
5. Open Sam's **Reviews** tab. If approval is off, the review is there with its stars. If approval is on, go to **Reviews** in the admin and click **Approve** under it.

## What members see

- A friendly "Thank you for sharing your thoughts in this review!" message, or, when approval is needed, a note that the review will show after admin approval.
- A **Reviews** tab with the review count on every profile.
- Sam's average rating in the profile header and, if **Member Directory Ratings** is on, in the members directory.
- Sam gets an email and a BuddyPress notification, unless you turned those off.

## Tips

- Members cannot review themselves. On your own profile there is no **Add Review** tab. To review someone, go to their profile.
- Logged-out visitors see "You must log in to add a review." with a link. After logging in, they come back to the review form.
- "Review" is just the default word. See [Change the wording and star color](change-labels-and-star-color.md).

## If it does not work

See [Troubleshooting](../troubleshooting/troubleshooting.md): "I cannot see Add Review" and "A new review is not showing on the profile".
