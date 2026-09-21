# Star Ratings and Criteria

Star ratings give members a quick, visual way to evaluate each other. You can use a single overall rating or several named criteria that capture different aspects of a member's reputation.

## How star ratings work

Reviews use a fixed 5-star scale. Each review stores a star value per criterion, and the plugin calculates an aggregate rating for each member from all of their published reviews. The scale is not configurable.

## Single vs multiple criteria

By default the criteria set has two entries seeded on activation, "Member Response" and "Member Skills". You control this on the **Criteria** tab under **WB Plugins > Member Reviews**.

- **Allow Multiple Criteria off**: reviewers rate a single overall value.
- **Allow Multiple Criteria on**: reviewers rate each criterion you have defined separately.

Example criteria sets you might build:

- Freelancer marketplace: Communication, Quality of Work, Timeliness, Professionalism.
- Tutoring community: Knowledge, Teaching Ability, Patience, Responsiveness.
- Professional network: Expertise, Reliability, Collaboration.

Keep the set focused. Three to five criteria give useful detail without overwhelming reviewers.

## Setting up criteria

1. Go to **WB Plugins > Member Reviews** and open the **Criteria** tab.
2. Toggle **Allow Multiple Criteria** on if you want per-aspect ratings.
3. Add, rename, or remove criteria rows.
4. Save changes.

Each active criterion appears as a separate star field in the review form and on each review.

A criterion's stored key is an identifier, not a label. The plugin stores the untranslated name as the key and translates it only when rendering, so renaming a criterion in the admin keeps existing reviews intact.

## How the aggregate rating is calculated

The aggregate uses a two-step average:

1. **Per-review average**: all criterion values in a single review are averaged.
2. **Member average**: all per-review averages are averaged together.

Example:

- Review 1: Communication 5, Quality 4, Timeliness 5, average 4.67.
- Review 2: Communication 4, Quality 5, Timeliness 4, average 4.33.
- Member aggregate: (4.67 + 4.33) / 2 = 4.5.

Each review contributes equally regardless of how many criteria it used. The aggregate and the review count are cached on the member and recalculated when reviews are approved, edited, trashed, or change status. A daily background job also reconciles the cached values.

## Where ratings display

- **Profile header**: the member's aggregate rating and review count.
- **Reviews tab**: each review with its per-criterion stars and overall score.
- **Members directory**: a star rating beside each member, when directory ratings are enabled on the General tab.

## Star color

Set the filled-star color on the **Display** tab. The default is `#FFC400`. The color applies to profiles, the directory, widgets, and the shortcode output.
