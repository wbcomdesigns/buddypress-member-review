# Star Ratings & Criteria

Star ratings give your community members a quick, visual way to evaluate each other. You can choose between a simple single-star rating or multiple rating criteria to capture different aspects of performance.

## How Star Ratings Work

Reviews use a 5-star scale where:
- **1 star** = Poor
- **2 stars** = Below Average
- **3 stars** = Average
- **4 stars** = Good
- **5 stars** = Excellent

Each review displays individual star ratings per criterion, and the system automatically calculates an overall average rating for each member.

![Review form with star rating criteria](../images/frontend-add-review-form.png)

## Single vs. Multiple Criteria

### Single Criterion Rating

Use a single rating when you want reviewers to give one overall score. This works well for:
- General community ratings
- Simple feedback systems
- Communities where detailed breakdown isn't needed

**Example**: "Overall Experience" (1-5 stars)

### Multiple Criteria Ratings

Multiple criteria let reviewers rate different aspects separately. This provides more detailed feedback and helps members understand their strengths and areas for improvement.

**Example criteria sets by community type:**

**Freelancer Marketplace:**
- Communication (How well they communicate)
- Quality of Work (Deliverable standards)
- Timeliness (Meeting deadlines)
- Professionalism (Work ethic and behavior)

**Tutoring Community:**
- Knowledge (Subject expertise)
- Teaching Ability (How well they explain concepts)
- Patience (Working with students)
- Responsiveness (Reply speed)

**Professional Network:**
- Expertise (Professional knowledge)
- Reliability (Dependability)
- Collaboration (Teamwork skills)
- Leadership (Guidance and mentoring)

## Setting Up Rating Criteria

You can configure rating criteria from your WordPress admin:

1. Go to **Settings → BP Member Reviews**
2. Navigate to the **Criteria** tab
3. Add, edit, or remove criteria
4. Set the display order
5. Save your changes

![Admin criteria settings](../images/admin-settings-criteria.png)

Each criterion you create appears as a separate star rating field in the review form.

## How Aggregate Ratings Are Calculated

The plugin calculates member ratings using a two-step average:

1. **Per-Review Average**: For each review, all criterion ratings are averaged together
2. **Overall Average**: All per-review averages are combined to create the member's overall rating

**Example Calculation:**

**Review 1:**
- Communication: 5 stars
- Quality: 4 stars
- Timeliness: 5 stars
- Review Average: 4.67 stars

**Review 2:**
- Communication: 4 stars
- Quality: 5 stars
- Timeliness: 4 stars
- Review Average: 4.33 stars

**Member's Overall Rating:** (4.67 + 4.33) ÷ 2 = **4.5 stars**

This approach ensures each review has equal weight regardless of how many criteria you use.

## Where Ratings Display

Star ratings appear in multiple locations throughout your BuddyPress site:

### Profile Header

The member's overall rating displays prominently at the top of their profile with the total number of reviews.

![Profile header showing star rating](../images/frontend-profile-header.png)

### Reviews Tab

Individual review ratings appear on the Reviews tab, showing:
- Overall review rating
- Individual criterion ratings
- Rating breakdown per criterion

### Member Directory

Member listings can display star ratings, helping visitors find highly-rated members quickly. This requires theme support or custom template modifications.

## Star Ratings & Member Ranking

Ratings impact member visibility and trust:

- **Higher ratings** increase credibility and trust
- **More reviews** provide social proof
- **Recent positive reviews** boost member reputation

Members with consistently high ratings across multiple criteria tend to attract more engagement.

## Best Practices

**For Site Administrators:**
- Choose criteria that matter to your community
- Use 3-5 criteria for detailed feedback (too many overwhelms reviewers)
- Name criteria clearly so reviewers understand what to rate
- Review and adjust criteria based on community feedback

**For Community Members:**
- Rate honestly and fairly across all criteria
- Consider each criterion separately rather than giving the same rating to all
- Use the full 1-5 scale (avoid always giving 5 stars or 3 stars)
- Explain your ratings in the written review content

## Tips for Choosing Criteria

**Keep it relevant**: Only include criteria that reviewers can reasonably evaluate based on their interaction.

**Make it clear**: Use simple, specific names like "Response Time" instead of vague terms like "Engagement."

**Stay focused**: 3-5 criteria provide enough detail without overwhelming reviewers.

**Test it out**: Create a few test reviews to see how the criteria work in practice before launching to your community.

---

Star ratings transform subjective feedback into actionable insights, helping your community members build trust and showcase their strengths.
