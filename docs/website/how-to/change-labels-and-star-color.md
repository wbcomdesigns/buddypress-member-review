# Change the Wording and Star Color

Goal: call reviews something else (for example "Testimonial" or "Recommendation") and change the color of the stars.

## Steps

1. Go to **WB Plugins > Member Reviews** and open the **Display** tab.
2. In **Review Label**, type the singular word, for example `Testimonial`. ("Label used in the profile tab and review form.")
3. In **Reviews Label (Plural)**, type the plural word, for example `Testimonials`.
4. Under **Colors**, click the box next to **Rating Color** and pick a color, or paste a color code such as `#e00000`.
5. Click **Save Changes**.

## What changes

| Setting | Where it shows |
|---|---|
| **Review Label** | Buttons and messages: "Add Testimonial", "Submit Testimonial", "Edit Testimonial", "Thank you for sharing your testimonial". |
| **Reviews Label (Plural)** | The tab name on every profile, the admin bar item, and the address of the tab. |
| **Rating Color** | Every filled and empty star: the profile, the review form, the directory, widgets and the shortcode. The default is yellow (`#FFC400`). |

The tab address follows the plural word. With "Reviews" it is `yoursite.com/members/sam/reviews/`. With "Testimonials" it is `yoursite.com/members/sam/testimonials/`. Links that people saved or shared with the old address stop working. Choose your words before you launch.

## Tips

- Do not use numbers in **Review Label**. The box warns "* Number input is not allowed *" and blocks saving until you remove them.
- Use a word that works in a sentence like "You must log in to add a testimonial". Labels are shown in lower case inside sentences.
- A few built-in sentences, such as "Please either select a rating or write a review.", always say "review". Only the label-based text changes.
- If the stars look the wrong color on a page, clear your page cache. The color is added as a style on the page.
- The wording only changes text typed into these boxes. Translations of the plugin do not translate your own words.

## If it does not work

- The new word is not showing: save the tab, then clear caches and reload.
- A saved link to the Reviews tab shows a "page not found": the address changed with the plural word. Use the new address from the member's profile menu.
