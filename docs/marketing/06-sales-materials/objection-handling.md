# Objection Handling Guide

Common objections and how to address them.

---

## Price Objections

### "It's too expensive"

**Understand:**
"What budget were you expecting for a review system?"

**Acknowledge:**
"I hear you. $69/year is a real cost, especially if you're not sure you need the Pro features."

**Address:**
"Here's how I'd think about it:

The free version is fully functional. Star ratings, moderation, notifications, owner replies — all included. Most communities don't need Pro.

Pro makes sense when you need:
- Testimonials instead of star ratings (courses/coaching)
- Skill endorsements (professional networks)
- Verified badges (marketplaces at scale)
- REST API (mobile apps, integrations)

If none of those fit your use case, stick with Free. It's not a crippled version — it's a complete product."

**Confirm:**
"Does that help clarify when Pro is worth it?"

---

### "Why pay when there's a free version?"

**Understand:**
"What made you consider Pro in the first place?"

**Acknowledge:**
"Fair question. The free version handles most use cases."

**Address:**
"Pro exists for specific needs:

1. **Review mode matters** — Star ratings work for marketplaces but feel wrong for courses (testimonials) or professional networks (endorsements). Pro lets you switch modes.

2. **Trust signals matter** — Verified badges show which reviews are from confirmed transactions. That's important at scale.

3. **API access** — Building a mobile app or external dashboard? You need the REST API.

4. **Support speed** — Free support is through WordPress.org forums. Pro gets direct email with faster response.

If your community is small and star ratings work, Free is genuinely fine."

**Confirm:**
"Which of those might be relevant to your community?"

---

### "Can I try before I buy?"

**Understand:**
"What specifically would you want to test?"

**Acknowledge:**
"Makes sense. You want to verify it works before spending money."

**Address:**
"We don't offer a trial, but here's what we do:

1. **Free version first** — Install the free plugin. Test star ratings, moderation, owner replies, reporting. 90% of the functionality is there.

2. **14-day refund policy** — If Pro doesn't meet your needs, we refund within 14 days. No hoops.

3. **Documentation** — Full docs and screenshots show exactly what Pro adds. You can see the features before buying.

Most customers install Free, run it for a month, then upgrade when they hit a limitation (usually needing testimonials or endorsements)."

**Confirm:**
"Would starting with Free and seeing how it goes work for you?"

---

## Feature Objections

### "Does it work with BuddyBoss?"

**Understand:**
"Are you using BuddyBoss Platform or the BuddyBoss Theme with BuddyPress?"

**Acknowledge:**
"Important question — compatibility matters."

**Address:**
"Yes, fully compatible.

We test with both:
- BuddyBoss Platform (their all-in-one solution)
- BuddyBoss Theme (their theme on top of BuddyPress)

Reviews appear on member profiles, integrate with activity streams, and respect BuddyBoss styling.

If you run into any compatibility issues, our support team handles them as priority bugs."

**Confirm:**
"Do you have BuddyBoss installed already? Happy to confirm on your specific setup."

---

### "Can members review themselves?"

**Understand:**
"Just want to confirm the expected behavior?"

**Acknowledge:**
"Good sanity check."

**Address:**
"No. The 'Add Review' button doesn't appear on your own profile. You can only review other members.

This is intentional — self-reviews would undermine the trust system."

**Confirm:**
"Does that cover your concern?"

---

### "What if someone abuses the review system?"

**Understand:**
"Are you worried about fake reviews, spam, or harassment?"

**Acknowledge:**
"Legitimate concern. Open review systems can be gamed."

**Address:**
"We've built multiple safeguards:

1. **Moderation queue** — Require admin approval before reviews publish (optional)

2. **Report system** — Any member can flag inappropriate reviews. After X flags (you set the threshold), reviews auto-hide.

3. **Owner replies** — Profile owners can respond once. Dialogue often resolves issues.

4. **Admin controls** — Edit, delete, or hide any review from the dashboard.

5. **Verified badges (Pro)** — Mark authentic reviews to distinguish them from potentially fake ones.

Plus, most abuse is obvious. A profile with 10 five-star reviews from new accounts is clearly gamed. The system makes that visible."

**Confirm:**
"Does that address your concern, or is there a specific abuse scenario you're thinking about?"

---

### "Can I customize the star colors / labels / criteria?"

**Understand:**
"What customization do you need?"

**Acknowledge:**
"Customization matters for brand consistency."

**Address:**
"Yes to all three:

**Star colors:** Settings > Display — pick your color.

**Labels:** Settings > Display — change 'Reviews' to 'Testimonials' or whatever fits.

**Criteria:** Settings > Criteria — add, edit, delete, reorder. Each criterion gets its own star rating.

Pro adds even more: stars, thumbs up/down, or text fields. Plus weighted averages."

**Confirm:**
"Does that cover what you need?"

---

### "We need an API for our mobile app"

**Understand:**
"What specifically do you need to do via API?"

**Acknowledge:**
"Mobile is important. API access is essential."

**Address:**
"Pro includes a full REST API.

Endpoints:
- GET /bupr/v1/reviews — List all reviews (filterable)
- POST /bupr/v1/reviews — Create a review
- GET /bupr/v1/reviews/{id} — Get single review
- PATCH /bupr/v1/reviews/{id} — Update a review
- DELETE /bupr/v1/reviews/{id} — Delete a review
- GET /bupr/v1/members/{id}/reviews — Reviews for a member
- GET /bupr/v1/members/{id}/rating — Member's aggregate rating

Uses WordPress REST authentication (cookie, application passwords, JWT compatible).

Full API documentation is in our docs site."

**Confirm:**
"Does that cover your integration needs?"

---

## Trust Objections

### "How long has this plugin been around?"

**Understand:**
"Are you concerned about stability or long-term support?"

**Acknowledge:**
"Smart to check. Plugin abandonment is real."

**Address:**
"BuddyPress Member Reviews has been actively developed since 2018.

Stats:
- 6+ years of continuous development
- 10,000+ active installations
- 4.8/5 rating on WordPress.org
- Regular updates (usually monthly)

We're Wbcom Designs — WordPress/BuddyPress specialists. This isn't a side project. It's part of our core plugin suite."

**Confirm:**
"Would you like to see our changelog or recent release history?"

---

### "What happens if I need help?"

**Understand:**
"What kind of support do you expect to need?"

**Acknowledge:**
"Support quality varies wildly with WordPress plugins. Good to verify."

**Address:**
"Two support tiers:

**Free:** WordPress.org forums. We respond within 24-48 hours during business days.

**Pro:** Direct email support. Faster response, higher priority.

Both get:
- Bug fixes (we don't abandon issues)
- Feature requests considered
- Documentation and guides

Pro customers can also request brief setup assistance if needed."

**Confirm:**
"What kind of support matters most to you?"

---

### "Will this slow down my site?"

**Understand:**
"Is site speed a current concern, or general due diligence?"

**Acknowledge:**
"Performance matters. Bloated plugins are a real problem."

**Address:**
"Member Reviews is lightweight.

- No external API calls
- No tracking scripts
- Assets only load where needed
- Efficient queries (we optimize for the member loop)

The main performance consideration is the Top Members shortcode — it queries reviews to calculate rankings. For large sites (10,000+ members), we recommend caching that output.

Core review display on profiles adds negligible load."

**Confirm:**
"Want me to share any specific benchmarks?"

---

## Competitor Objections

### "We're looking at [other plugin]"

**Understand:**
"What about [competitor] appeals to you?"

**Acknowledge:**
"Good to compare options."

**Address:**
"I can't speak to their plugin specifically, but here's what sets us apart:

1. **BuddyPress-native** — Reviews live on member profiles, integrate with activity, respect BP permissions. We're not a generic review plugin adapted for BP.

2. **Three review modes** — Ratings, Testimonials, or Endorsements. Most competitors only do star ratings.

3. **Active development** — 6+ years, regular updates, responsive support.

4. **Free is functional** — Our free version isn't crippled to force upgrades. It's a complete product.

That said, if another plugin fits your needs better, use it. The goal is solving your problem, not winning a feature comparison."

**Confirm:**
"What features are most important for your decision?"

---

### "Why not build it ourselves?"

**Understand:**
"Do you have development resources available?"

**Acknowledge:**
"If you have the team, custom development gives you exactly what you want."

**Address:**
"Rough estimate for a custom review system:

- Database schema + CRUD: 20+ hours
- Frontend templates + AJAX: 30+ hours
- Admin interface: 15+ hours
- Moderation, notifications, reporting: 25+ hours
- Testing + bug fixes: 20+ hours

That's 100+ developer hours minimum. At $50-100/hour, you're looking at $5,000-10,000.

Plus ongoing maintenance: WordPress updates, BuddyPress compatibility, security patches.

Member Reviews Pro is $69-299/year and does all of that.

Build custom if you need something truly unique. Use a plugin if your needs are standard."

**Confirm:**
"What custom requirements do you have that the plugin might not cover?"

---

## Closing

### After addressing objections

"Does that address your concerns? Any other questions before deciding?"

"Want me to send you the documentation links so you can review at your own pace?"

"If you'd like, I can schedule a quick call to walk through your specific use case."

