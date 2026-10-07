# Show Top-Rated Members on a Page

Goal: put a list of your best-rated (or most-reviewed) members on a page, post or sidebar.

You have two ways: a shortcode for pages and posts, or a widget for sidebars. Both only list members who have at least one published review.

## Option 1: the shortcode

1. Edit the page or post where you want the list.
2. Add a **Shortcode** block (or use the Classic editor).
3. Type: `[bupr_display_top_members]`
4. Update the page.

You will see up to 5 members, best average rating first, each with picture, name, stars and the line "Rating: 4.5/5 (3 reviews)".

Change it with settings inside the brackets:

| To do this | Type |
|---|---|
| Add a heading | `[bupr_display_top_members title="Top Rated Members"]` |
| Show 10 members | `[bupr_display_top_members total_member="10"]` |
| List by number of reviews instead | `[bupr_display_top_members type="top reviewed"]` |
| Hide pictures | `[bupr_display_top_members avatar="hide"]` |

You can combine them: `[bupr_display_top_members title="Most Reviewed" type="top reviewed" total_member="8" avatar="hide"]`

The **Display** tab at **WB Plugins > Member Reviews** also lists these settings, for easy copying.

## Option 2: the widget

1. Go to **Appearance > Widgets**.
2. Add the **BP Member Review Widget** to a sidebar.
3. Fill in the boxes:
   - **Enter Title**: the heading.
   - **Display Members**: how many members to list (20 by default).
   - Choose **Top Rated** or **Most Reviewed**.
   - **Display Avatar**: Show or Hide.
4. Click **Save**.

On a block theme, add the widget through the **Legacy Widget** block.

## What visitors see

A list of members with their picture, a link to their profile, stars and the review count. The review count links to the member's **Reviews** tab.

## Tips

- The list is saved for about 15 minutes to keep your site fast. A brand-new review may take a few minutes to change the order.
- Members whose reviews have no stars (text only) do not appear in a top-rated list, because they have no average yet. They can appear in a most-reviewed list.
- If the list says "No members have been reviewed yet.", nobody has a published review yet.
- On a member's own profile, use the **BP Displayed Member Rating Widget** to show that member's individual ratings. See [Widgets](../shortcodes/widgets.md).

## If it does not work

- Nothing shows: make sure the shortcode is typed exactly, with straight quote marks.
- The order looks wrong: wait a few minutes and reload. See "Ratings look wrong" in [Troubleshooting](../troubleshooting/troubleshooting.md).
