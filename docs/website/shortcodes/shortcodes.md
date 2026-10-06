# Shortcode

The free plugin provides one shortcode, `[bupr_display_top_members]`, which renders a list of your top-rated or most-reviewed members on any page or post.

## Basic usage

```
[bupr_display_top_members]
```

With no attributes this lists the top 5 members by aggregate rating, with avatars.

## Attributes

| Attribute | Type | Default | Description |
|---|---|---|---|
| `title` | string | empty | Heading shown above the list. |
| `total_member` | number | 5 | How many members to list. |
| `type` | string | `top rated` | `top rated` orders by aggregate rating; `top reviewed` orders by review count. |
| `avatar` | string | `show` | `show` or `hide` member avatars. |

## Examples

Top 10 by rating:

```
[bupr_display_top_members total_member="10" type="top rated"]
```

Most reviewed, no avatars, with a heading:

```
[bupr_display_top_members title="Most Reviewed" type="top reviewed" total_member="8" avatar="hide"]
```

## Where to use it

- **Pages and posts**: paste the shortcode into the block editor (Shortcode block) or the classic editor.
- **Widget areas**: use a Shortcode widget or block.
- **Templates**: `<?php echo do_shortcode( '[bupr_display_top_members total_member="5"]' ); ?>`
- **Page builders**: use the builder's shortcode or HTML element.

## Styling

The output uses these classes:

```css
.bupr-shortcode-top-members-contents /* outer wrapper */
.bupr_members_review_setting         /* wrapper class */
.bupr-member-main                    /* the <ul> list */
.bupr-members                        /* each <li> item */
.bupr-img-widget                     /* avatar wrapper */
.bupr-content-widget                 /* content wrapper */
.bupr-member-title                   /* member name link */
.bupr-member-rating                  /* star rating area */
.bupr-meta                           /* rating text */
```

Add custom rules under **Appearance > Customize > Additional CSS** or in your theme.

The result is cached for about 15 minutes and refreshed automatically when ratings change, so a new review may take a few minutes to appear in the list.
