# Endorsements Mode

**Pro Feature** — Requires [BuddyPress Member Reviews Pro](https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro/)

## What is Endorsements Mode?

Endorsements Mode transforms generic reviews into LinkedIn-style skill endorsements. Members endorse each other for specific skills, building professional credibility through peer validation.

## The Problem with Generic Reviews

Star ratings and generic reviews don't fit professional networks. They lack context. An endorsement system answers specific questions: "What is this person good at?" and "Who can vouch for their skills?"

## What Changes

**Interface Updates:**
- Profile button becomes "Endorse [Name]"
- Star ratings are replaced with skill checkboxes
- A new "Endorsements" tab appears on profiles
- All labels update to endorsement language
- Skills Cloud appears at the top of the Endorsements tab

**Skills Cloud Feature:**
Endorsed skills are aggregated and displayed as tags with count badges at the top of the Endorsements tab, before the endorsement list. This shows at a glance which skills the member is most recognized for.

Example: Leadership (5), Communication (3), Problem Solving (2)

## How to Enable

1. Go to **WordPress Admin > Settings > Member Reviews**
2. Click the **Pro** tab
3. Find **Review Mode** setting
4. Select **Endorsements**
5. Click **Save Changes**

## The Endorsement Flow

### Endorsing Others

1. **Visit a Member Profile** — Click the "Endorse [Name]" button

![Endorse Button on Profile](../images/endorsement-button-profile.png)

2. **Select Skills** — A modal opens showing available skills in a grid layout

![Endorsement Modal with Skills](../images/endorsement-modal-skills.png)

3. **Choose Skills** — Check the boxes for skills you can genuinely endorse
4. **Add Context (Optional)** — Write a message explaining your endorsement
5. **Submit** — The endorsement appears on their profile after approval (if moderation is enabled)

### Requesting Endorsements

When viewing your own profile:
1. Click "Request Endorsement"
2. Select members to request endorsements from
3. Optionally suggest specific skills you'd like endorsed
4. Members receive notifications about your request

### Managing Requests

The **Pending Requests** tab shows endorsement requests you've received:
- **Approve** — Accept the request and write your endorsement
- **Decline** — Politely decline if you don't feel qualified to endorse

### Editing Endorsements

After submitting, you can edit your endorsement:
- Change selected skills
- Update your message
- Remove the endorsement entirely

## Skill Management

### Default Skills

The plugin includes 10 professional skills out of the box:
- Leadership
- Communication
- Problem Solving
- Teamwork
- Creativity
- Technical Skills
- Project Management
- Time Management
- Adaptability
- Attention to Detail

### Customizing Skills

**Enable/Disable Skills:**
1. Go to **Settings > Member Reviews > Pro**
2. Find **Skills Management** section
3. Toggle skills on or off

![Skills Management Toggles](../images/pro-features-toggles-skills.png)

**Add Custom Skills:**
Add skills specific to your niche: "WordPress Development," "Patient Care," "Sales Strategy," or "Graphic Design."

## Display Skills with Shortcode

Show a member's endorsed skills anywhere using the shortcode:

```
[bupr_member_skills]
```

**Parameters:**

| Parameter | Description | Default |
|-----------|-------------|---------|
| `user_id` | Display skills for specific user | Current profile user |
| `limit` | Maximum skills to show | All skills |

**Examples:**

```
[bupr_member_skills user_id="15"]
[bupr_member_skills limit="5"]
[bupr_member_skills user_id="15" limit="5"]
```

## Combine with Verified Badges

Add Verified Badges (also a Pro feature) to endorsements for extra credibility. A verified endorsement from a recognized expert carries significant weight.

## Use Cases

### Professional Network
Build a LinkedIn-style community where members showcase validated skills and expertise.

### Expert Directory
Help visitors find specialists by browsing endorsed skills. The Skills Cloud makes it easy to see a member's strongest areas.

### Freelancer Platform
Let clients endorse freelancers for completed projects. Skill endorsements build portfolio credibility beyond generic 5-star ratings.

## Configuration Tips

**Limit Skill Selection:** Don't enable too many skills. 10-15 focused skills work better than 50 generic ones.

**Encourage Specific Endorsements:** Remind members to add context in their messages. "Great communicator" means more when explained.

**Moderate Strategically:** Enable moderation for new communities to ensure quality. Disable it once trust is established.

**Notify Members:** Enable email notifications when members receive endorsements to keep engagement high.

## Next Steps

- Learn about [Testimonials Mode](../testimonials-mode/testimonials-mode.md) for story-based feedback
- Explore [Verified Badges](../verified-badges/verified-badges.md) to add trust signals
- Configure [Email Notifications](../notifications/email-notifications.md) to engage members
