# Widgets

The plugin adds two widgets. Both are classic widgets. On a block theme, add them with the **Legacy Widget** block.

Find widgets at **Appearance > Widgets** (or in the Customizer under **Widgets**).

## BP Member Review Widget

Lists your top-rated or most-reviewed members. It can go in any widget area.

| Field | What it does | Default |
|---|---|---|
| **Enter Title** | Heading above the list. Leave it empty for no heading. | "Top Members" |
| **Display Members** | How many members to list. | 20 |
| **Top Rated** or **Most Reviewed** | Order by average rating, or by number of reviews. | Top Rated |
| **Display Avatar** | Show or hide member pictures. | Show |

Each entry shows the picture, the member's name linked to their profile, stars and a line such as "4.5/5 (3 reviews)". Only members with at least one published review appear. If there are none, the widget keeps its heading and shows "No members have been reviewed yet." inside the widget box.

## BP Displayed Member Rating Widget

Shows the individual ratings that the member whose profile you are viewing has received. It only appears on a member profile page and stays hidden everywhere else, so put it in a sidebar that shows on profiles.

| Field | What it does | Default |
|---|---|---|
| **Enter Title:** | Heading above the list. Leave it empty for no heading. | "Member Ratings" |
| **Number of Ratings to Show:** | How many ratings are listed. Leave it empty or set 0 to list them all (up to the 100 most recent). | 5 |
| **Default Sorting:** | **Latest**, **Highest** or **Lowest**. | Latest |

Each entry shows the reviewer's picture and name (or the default picture and "Anonymous") and the stars, with the score as "4/5".

If the member has no rated reviews, the widget says "The member has not received a rating yet."

Things to know:

- An anonymous review shows the default picture and "Anonymous", never the reviewer's own picture or name.
- It lists stars only, not the written text of the reviews.
- **Highest** and **Lowest** sort the member's 100 most recent ratings.

## Styling (for designers)

BP Member Review Widget:

```css
.bupr-member-main      /* the list */
.bupr-members          /* one member */
.bupr-img-widget       /* picture */
.bupr-content-widget   /* name, stars and text */
.bupr-member-title     /* member name */
.bupr-member-rating    /* stars */
.bupr-meta             /* the score line */
```

BP Displayed Member Rating Widget:

```css
#bp-member-rating      /* the list */
.vcard                 /* one rating */
.item-avatar           /* picture */
.item-title            /* reviewer name */
.item-meta             /* stars and score */
.bupr-star-rate        /* a star */
```

Both widgets: `.bupr-widget-empty` is the "no reviews yet" sentence.

Both widgets follow your theme's widget box: the title comes from the theme, rows sit side by side with the picture (50px, rounded corners), and colours follow the theme, including dark mode.

Add your own rules under **Appearance > Customize > Additional CSS**.
