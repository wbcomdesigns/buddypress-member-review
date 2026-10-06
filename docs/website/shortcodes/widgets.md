# Widgets

The plugin ships two classic `WP_Widget` widgets. On a block theme, add them through the Legacy Widget block.

## BP Member Review Widget

Lists top-rated or most-reviewed members in any widget area.

| Setting | Values | Description |
|---|---|---|
| Title | text | Heading above the list. |
| Display | Top Rated (`top-rated`) or Most Reviewed (`top-viewed`) | Order by aggregate rating or by review count. |
| Number of members | number | How many members to show. |
| Show avatar | Show or Hide | Whether to display member avatars. |

Add it under **Appearance > Widgets** (or the Customizer): find **BP Member Review**, place it in a widget area, configure the fields, and save.

## BP Displayed Member Rating Widget

Shows the reviews of the member whose profile is currently being viewed. It is contextual and only renders on a member profile.

| Setting | Values | Description |
|---|---|---|
| Title | text | Heading above the list. If left empty, the member's name is used on the front end. |
| Number of reviews | number | How many reviews to show. |
| Sort by | Latest (`latest`), Highest (`highest`), or Lowest (`lowest`) | Order reviews by date or rating. |

Place it in a widget area that appears on member profiles. It hides itself anywhere that is not a member profile.

## Styling

BP Member Review Widget:

```css
.bupr-member-main   /* the <ul> list */
.bupr-members       /* each <li> item */
.bupr-img-widget    /* avatar wrapper */
.bupr-content-widget/* content wrapper */
.bupr-member-title  /* member name */
.bupr-member-rating /* star rating area */
.bupr-meta          /* rating text */
```

BP Displayed Member Rating Widget:

```css
#bp-member-rating   /* the <ul> list */
.vcard              /* each <li> item */
.item-avatar        /* avatar wrapper */
.item               /* content area */
.item-title         /* reviewer name */
.bupr-star-rate     /* star rating display */
.bupr-meta          /* rating text */
```

Add custom rules under **Appearance > Customize > Additional CSS** or in your theme.
