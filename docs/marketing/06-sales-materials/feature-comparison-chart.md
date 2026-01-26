# Feature Comparison Chart

Detailed feature comparison for sales and decision support.

---

## Free vs Pro Comparison

| Feature | Free | Pro |
|---------|:----:|:---:|
| **Core Features** | | |
| Star ratings (1-5) | ✓ | ✓ |
| Multiple rating criteria | ✓ | ✓ |
| Written review text | ✓ | ✓ |
| Anonymous review option | ✓ | ✓ |
| Owner replies | ✓ | ✓ |
| Report system | ✓ | ✓ |
| Auto-hide after report threshold | ✓ | ✓ |
| Moderation queue | ✓ | ✓ |
| Email notifications | ✓ | ✓ |
| BuddyPress notifications | ✓ | ✓ |
| Top Members shortcode | ✓ | ✓ |
| Template overrides | ✓ | ✓ |
| Activity stream integration | ✓ | ✓ |
| Profile header rating display | ✓ | ✓ |
| Member directory integration | ✓ | ✓ |
| **Review Modes** | | |
| Ratings Mode (stars) | ✓ | ✓ |
| Testimonials Mode (text-only) | — | ✓ |
| Endorsements Mode (skills) | — | ✓ |
| **Trust Features** | | |
| Verified badges | — | ✓ |
| Admin verification | — | ✓ |
| Tenure-based auto-verification | — | ✓ |
| **Advanced Criteria** | | |
| Stars input type | ✓ | ✓ |
| Thumbs up/down input type | — | ✓ |
| Text input type | — | ✓ |
| Weighted averages | — | ✓ |
| **Developer Tools** | | |
| REST API | — | ✓ |
| Hooks and filters | ✓ | ✓ |
| **Support** | | |
| WordPress.org forum support | ✓ | ✓ |
| Priority email support | — | ✓ |
| **Updates** | | |
| WordPress.org updates | ✓ | ✓ |
| Pro feature updates | — | ✓ |

---

## Review Mode Comparison

| Aspect | Ratings | Testimonials | Endorsements |
|--------|---------|--------------|--------------|
| **Input Type** | Star ratings | Text field | Skill selection |
| **Aggregate Score** | Yes (1-5) | No | Yes (count per skill) |
| **Written Feedback** | Optional | Required | Optional |
| **Profile Display** | Stars + count | Quote + author | Skill badges |
| **Best For** | Marketplaces | Courses | Professional networks |
| **Quantitative** | Yes | No | Yes (counts) |
| **Qualitative** | Optional | Yes | Optional |

---

## Criteria Type Comparison (Pro)

| Type | Input | Output | Best For |
|------|-------|--------|----------|
| **Stars** | 1-5 selection | Star display | Quality ratings |
| **Thumbs** | Up/Down buttons | Percentage | Yes/No questions |
| **Text** | Short text field | Text display | Open feedback |

---

## Use Case Fit Matrix

| Community Type | Recommended Mode | Key Features | Plan |
|---------------|------------------|--------------|------|
| **Freelancer Marketplace** | Ratings | Verified badges, multiple criteria, API | Pro |
| **Service Directory** | Ratings | Star ratings, owner replies, moderation | Free |
| **Course Platform** | Testimonials | Text-only reviews, no stars | Pro |
| **Coaching Site** | Testimonials | Success stories, owner replies | Pro |
| **Professional Network** | Endorsements | Skill badges, LinkedIn-style | Pro |
| **Expert Directory** | Endorsements | Skill counts, API | Pro |
| **Gaming Community** | Ratings | Star ratings, Top Members | Free |
| **Hobby Forum** | Ratings | Basic ratings, moderation | Free |
| **Membership Site** | Testimonials or Ratings | Depends on content type | Either |
| **Alumni Network** | Endorsements | Skill vouching | Pro |

---

## Pricing Tier Comparison

| Plan | Sites | Price/Year | Per-Site Cost |
|------|-------|------------|---------------|
| Single | 1 | $69 | $69 |
| 5-License | 5 | $129 | $25.80 |
| Starter | 25 | $199 | $7.96 |
| Developer | Unlimited | $299 | $0* |

*Developer license is unlimited sites.

---

## Support Comparison

| Aspect | Free | Pro |
|--------|------|-----|
| **Channel** | WordPress.org forums | Direct email |
| **Response Time** | 24-48 hours | Same day (business hours) |
| **Bug Fixes** | Yes | Priority |
| **Feature Requests** | Considered | Prioritized |
| **Setup Help** | Documentation only | Assistance available |
| **Custom Development** | No | Quoted separately |

---

## Compatibility Matrix

| Platform | Free | Pro |
|----------|:----:|:---:|
| WordPress 6.0+ | ✓ | ✓ |
| WordPress 6.7+ | ✓ | ✓ |
| BuddyPress 12.0+ | ✓ | ✓ |
| BuddyBoss Platform | ✓ | ✓ |
| BuddyBoss Theme | ✓ | ✓ |
| Youzify | ✓ | ✓ |
| PHP 7.4+ | ✓ | ✓ |
| PHP 8.0+ | ✓ | ✓ |
| PHP 8.1+ | ✓ | ✓ |
| PHP 8.2+ | ✓ | ✓ |

---

## API Endpoint Summary (Pro)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/bupr/v1/reviews` | GET | List reviews (filterable) |
| `/bupr/v1/reviews` | POST | Create review |
| `/bupr/v1/reviews/{id}` | GET | Get single review |
| `/bupr/v1/reviews/{id}` | PATCH | Update review |
| `/bupr/v1/reviews/{id}` | DELETE | Delete review |
| `/bupr/v1/reviews/{id}/reply` | POST | Add owner reply |
| `/bupr/v1/reviews/{id}/report` | POST | Report review |
| `/bupr/v1/members/{id}/reviews` | GET | Reviews for member |
| `/bupr/v1/members/{id}/rating` | GET | Member aggregate rating |

---

## Shortcode Parameters

### [bupr_display_top_members]

| Parameter | Description | Default |
|-----------|-------------|---------|
| `max` | Maximum members to display | 10 |
| `min_reviews` | Minimum reviews required | 1 |
| `member_type` | Filter by BP member type | (all) |
| `orderby` | Sort order (rating, review_count) | rating |
| `layout` | Display style (grid, list) | grid |

---

## Template Files

| Template | Purpose | Override Path |
|----------|---------|---------------|
| `bupr-reviews-tab-template.php` | Reviews list on profile | `theme/review/` |
| `bupr-single-review-template.php` | Individual review display | `theme/review/` |
| `bupr-edit-review-form.php` | Review edit modal | `theme/review/` |
| `bupr-add-review-form.php` | New review form | `theme/review/` |

