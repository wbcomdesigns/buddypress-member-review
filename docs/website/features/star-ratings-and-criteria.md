# Star Ratings and Criteria

Star ratings give members a quick, visual way to judge each other. You choose whether members give one overall rating or rate several things separately.

## The 5-star scale

Every rating is 1 to 5 stars. The scale cannot be changed. A review with text only (no stars) is allowed, as long as the member writes something.

## One rating or several criteria

You choose on **WB Plugins > Member Reviews > Criteria**, using the **Multiple Criteria** switch.

| Setting | What members see | Good for |
|---|---|---|
| **Multiple Criteria** on | One row of stars per criterion in your list, for example "Communication" and "Quality of Work". | Communities where different qualities matter. |
| **Multiple Criteria** off | A single row of stars labeled "Rating". | Simple communities that want one score. |

To keep a single overall rating, switch **Multiple Criteria** off and leave at least one row in the criteria list. The name of that row is not shown to members. If you delete every row, the review form has no stars at all and members can only write text.

Two criteria are created for you when you activate the plugin: "Member Response" and "Member Skills".

### Example criteria

- Freelancer marketplace: Communication, Quality of Work, Timeliness.
- Tutoring community: Knowledge, Patience, Responsiveness.
- Professional network: Expertise, Reliability, Collaboration.

Three to five criteria give useful detail without making the form tiring to fill in.

## What happens when you rename or remove a criterion

Each review remembers the criterion name it was written under.

- **Renaming**: old reviews keep showing the old name. New reviews use the new name. Both still count toward the member's average.
- **Removing**: old reviews that used it still show their stars under the old name. New reviews no longer ask for it.

So changing criteria never deletes anyone's rating. If you want a clean slate, decide on your criteria before opening reviews to your members.

## What a member must fill in

A review can be submitted when the member either:

- rates every criterion (or gives the single rating), or
- writes review text.

If they do neither, the form shows "Please either select a rating or write a review." and nothing is saved. A review with text but no stars is saved and shows no stars.

## How a member's average rating is worked out

The average is calculated in two steps from the member's **published** reviews:

1. For each review, the stars are averaged. A review that rated "Communication" 5 and "Quality" 4 scores 4.5.
2. The review scores are averaged together.

Example:

- Review 1: Communication 5, Quality 4, Timeliness 5. Review score 4.67.
- Review 2: Communication 4, Quality 5, Timeliness 4. Review score 4.33.
- The member's average: (4.67 + 4.33) / 2 = 4.5.

Notes:

- Every review counts equally, whatever the number of criteria.
- Reviews with no stars (text only) count in the review total but not in the average.
- Pending, hidden and trashed reviews do not count.
- Deleting, trashing, approving or hiding a review updates the member's average and count straight away. A daily background job also double-checks the numbers.

## Where ratings show

| Where | What shows |
|---|---|
| Member profile header | The average rating and review count. Hover over the stars to see "Average rating". |
| Reviews tab | Each review with its stars and the overall number. |
| Members directory | The average rating beside each member who has reviews. |
| Top members shortcode and widgets | See [Shortcodes](../shortcodes/shortcodes.md) and [Widgets](../shortcodes/widgets.md). |

The profile header and directory ratings are both controlled by one switch, **Member Directory Ratings**, on the General tab. Turn it off and both disappear.

## Star color

Change the star color on the **Display** tab. See [Change the wording and star color](../how-to/change-labels-and-star-color.md).

## Accessibility

Members can fill in stars with the keyboard: use the arrow keys to change the value, and Enter or Space to set it.
