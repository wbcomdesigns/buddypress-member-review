# Re-engagement Email Sequence

Win-back campaigns for inactive users.

---

## Trigger Criteria

Send this sequence when:
- Plugin installed but no reviews submitted in 30+ days
- OR Plugin was active, then deactivated
- OR License expired and not renewed (Pro)

---

## Sequence A: No Activity (Plugin Installed, No Reviews)

### Email 1: Check-in

**Trigger:** 30 days after installation, 0 reviews submitted
**Subject Lines (A/B):**
- A: "Is Member Reviews working for you?"
- B: "Quick question about your review system"

#### Body

Hi,

I noticed you installed BuddyPress Member Reviews a month ago, but no reviews have been submitted yet.

**Is everything okay?**

Common reasons this happens:
1. **Members don't know they can review** → Add a "Review this member" CTA to profiles
2. **Configuration confusion** → Reply here and I'll help troubleshoot
3. **Waiting for more members** → Makes sense. Reviews work better at scale.
4. **It's not the right fit** → That's fine too. Let me know and I'll remove you from these emails.

If you need help getting started, I'm here.

— Varun, Wbcom Designs

---

### Email 2: Quick Win

**Trigger:** 7 days after Email 1 (if still no reviews)
**Subject:** "Get your first review in 5 minutes"

#### Body

Hi,

Still no reviews on your BuddyPress site? Here's a quick way to seed the system:

**Step 1:** Create a test member account (or use an existing member)

**Step 2:** Log in as that member and visit another profile

**Step 3:** Click the Reviews tab and submit a review

**Step 4:** Log in as admin and approve it (if moderation is on)

That's it. One review on your site shows members that the system exists and is being used.

**Pro tip:** Ask a few trusted community members to leave honest reviews. Social proof begets more reviews.

---

Still stuck? Reply here with your site URL and I'll take a look.

— Varun

---

## Sequence B: Deactivated Plugin

### Email 1: Feedback Request

**Trigger:** 3 days after plugin deactivation
**Subject:** "We noticed you deactivated Member Reviews"

#### Body

Hi,

Looks like you deactivated BuddyPress Member Reviews.

No hard feelings — but I'd love to know why so we can improve.

**What happened?**

A) Found a bug or error
B) Missing a feature I needed
C) Too complicated to set up
D) My community didn't use it
E) Switched to a different plugin
F) Just testing / not ready yet

Reply with a letter (or more details if you're willing). Your feedback shapes our roadmap.

— Varun, Wbcom Designs

---

### Email 2: Based on Response

**Trigger:** Based on reply to Email 1

#### Response A (Bug/Error)

**Subject:** "Let me help fix that"

Hi,

Sorry you ran into a bug. Can you share:
1. What happened (error message, unexpected behavior)
2. Your WordPress and BuddyPress versions
3. Your site URL (if you're comfortable sharing)

I'll look into it personally and get back to you.

— Varun

---

#### Response B (Missing Feature)

**Subject:** "What feature would have helped?"

Hi,

Thanks for the feedback. What feature were you looking for?

If it's something we're already building, I can let you know the timeline. If it's a new idea, I'll add it to our feature requests.

— Varun

---

#### Response C (Too Complicated)

**Subject:** "Let me help you set it up"

Hi,

I'm sorry the setup was confusing. Want me to configure it for you?

Send me your site URL and admin access (or create a temporary admin account), and I'll set up Member Reviews the way you want it.

No charge — I want to make sure the plugin works for you.

— Varun

---

#### Response D (Community Didn't Use It)

**Subject:** "Tips for encouraging reviews"

Hi,

It's common for members to not use reviews unless prompted. A few things that help:

1. **Seed with examples** — Get 5-10 early reviews so members see the system is active
2. **Prompt after transactions** — If you run a marketplace, email buyers asking them to review sellers
3. **Feature top reviewers** — Use the Top Members shortcode on your homepage

Would any of these work for your community?

— Varun

---

## Sequence C: Expired Pro License

### Email 1: Expiration Notice

**Trigger:** 7 days before license expiration
**Subject:** "Your Member Reviews Pro license expires in 7 days"

#### Body

Hi,

Your BuddyPress Member Reviews Pro license expires on [date].

**What happens after expiration:**
- Pro features continue working
- You lose access to updates
- You lose priority support

**To renew:**
Log in to your Wbcom Designs account and renew your license.

https://wbcomdesigns.com/your-account/

---

If you're not planning to renew, no action needed. The plugin will keep working — you just won't get updates.

— Wbcom Designs

---

### Email 2: Expired

**Trigger:** 1 day after expiration
**Subject:** "Your Pro license has expired"

#### Body

Hi,

Your BuddyPress Member Reviews Pro license expired on [date].

**Current status:**
- Pro features: Still active
- Updates: No longer available
- Support: Downgraded to community forums

**To restore full access:**
Renew your license from your account page.

---

No pressure to renew if Pro isn't worth it for you. But if you've been using Testimonials Mode, Endorsements, or the REST API, you'll want updates for compatibility with future WordPress and BuddyPress releases.

— Wbcom Designs

---

### Email 3: Win-back Offer

**Trigger:** 30 days after expiration (if not renewed)
**Subject:** "25% off to come back"

#### Body

Hi,

It's been a month since your Pro license expired.

If you've been meaning to renew but haven't gotten around to it, here's a reason to do it now:

**25% off any Pro renewal**

Use code: COMEBACK25

| Plan | Regular | With Code |
|------|---------|-----------|
| Personal | $49 | $37 |
| Professional | $99 | $74 |
| Agency | $129 | $97 |

---

https://wbcomdesigns.com/your-account/

Code expires in 7 days.

— Wbcom Designs

---

## Unsubscribe Handling

After any re-engagement email:
- Provide clear unsubscribe link
- Honor unsubscribes immediately
- Don't add back to other sequences

If user replies negatively:
- Remove from all marketing emails
- Keep on critical security/update notifications only

