# Shortcodes

The plugin has one shortcode, `[bupr_display_top_members]`. It lists your best-rated or most-reviewed members on any page or post.

For a step-by-step guide, see [Show top-rated members on a page](../how-to/show-top-rated-members.md).

## Basic use

```
[bupr_display_top_members]
```

With nothing added, it lists the top 5 members by average rating, with their pictures.

## Options

| Option | What it does | Default | Values |
|---|---|---|---|
| `title` | Heading above the list. | none | Any text |
| `total_member` | How many members to list. | 5 | A number |
| `type` | How to order the list. | `top rated` | `top rated` (highest average rating first) or `top reviewed` (most reviews first) |
| `avatar` | Show or hide member pictures. | `show` | `show` or `hide` |

## Examples

Top 10 by rating, with a heading:

```
[bupr_display_top_members title="Top Rated Members" total_member="10"]
```

Most reviewed, no pictures:

```
[bupr_display_top_members title="Most Reviewed" type="top reviewed" total_member="8" avatar="hide"]
```

## What visitors see

For each member: picture (if shown), name linked to their profile, five stars, and a line such as "Rating: 4.5/5 (3 reviews)". The review count links to the member's **Reviews** tab. If no one has a published review yet, the list says "No members have been reviewed yet."

Only members with a published review are listed. In `top rated` order, a member also needs at least one star rating.

## Where to put it

- **Pages and posts**: add a **Shortcode** block and type it in.
- **Sidebars**: use a **Shortcode** block or a Text widget. (There is also a ready-made widget. See [Widgets](widgets.md).)
- **Page builders**: use the builder's shortcode element.
- **Theme files** (for developers): `<?php echo do_shortcode( '[bupr_display_top_members total_member="5"]' ); ?>`

## Speed and freshness

The list is saved for about 15 minutes to keep your site fast. It refreshes sooner when a review is approved, hidden, trashed or recalculated, but a new review may take a few minutes to show up.

## Styling (for designers)

The shortcode uses these CSS classes. Add your own rules under **Appearance > Customize > Additional CSS**.

```css
.bupr-shortcode-top-members-contents  /* outer wrapper */
.bupr-member-main                     /* the list */
.bupr-members                         /* one member */
.bupr-img-widget                      /* picture */
.bupr-content-widget                  /* name, stars and text */
.bupr-member-title                    /* member name */
.bupr-member-rating                   /* stars */
.bupr-meta                            /* the "Rating: 4.5/5" line */
```

Star color is set at **WB Plugins > Member Reviews > Display > Rating Color**.
