# Notifications and Activity

The plugin can tell people when something happens to a review: by email, by a BuddyPress notification, and with an entry in the activity stream. You can switch each one on or off separately on **WB Plugins > Member Reviews > General**.

## Who gets told what

| Event | Reviewed member | You (the site administrator) |
|---|---|---|
| A review is published at once (Auto Approve on) | Email and BuddyPress notification | Nothing |
| A review needs approval (Auto Approve off) | Nothing yet | Email, and a BuddyPress notification for the first administrator account |
| You approve a pending review | Email and BuddyPress notification | Nothing |
| Someone reports a review | Nothing | Nothing. Look at the Overview tab or the Reported list. |
| The member replies to a review | Nothing | Nothing |

## The switches

| Setting | What it controls |
|---|---|
| **Email Notifications** | The email to the reviewed member, and the "needs approval" email to you. |
| **BuddyPress Notifications** | The notification bubble the reviewed member sees in BuddyPress. |
| **Activity Stream** | Whether a published review is announced in the site-wide activity stream. Off by default. |

Both notification switches are on by default. The BuddyPress notification to the administrator for a pending review is sent only while **Email Notifications** is on.

## The emails

The emails sent to the reviewed member and to you each have a subject and a message you can edit on the General tab. The boxes come pre-filled with friendly text.

| Field | Sent to | Placeholders you can use |
|---|---|---|
| **Email Subject** and **Email Message** | The member who got the review | Subject: `[site-name]`. Message: `[user-name]`, `[reviewer-name]`, `[review-link]`, `[site-name]` |
| **Approval Email Subject** and **Approval Email Message** | The site administrator email (set in **Settings > General**) | Subject: `[site-name]`. Message: `[site-admin]`, `[review-aproval-link]`, `[site-name]` |

Notes:

- Placeholders are replaced when the email is sent. Type them exactly as shown. The approval link placeholder really is spelled `[review-aproval-link]` (one "p").
- The two approval fields only appear while **Auto Approve Reviews** is off.
- Emails are sent as formatted text, so line breaks and basic formatting in the message box are kept.
- For an anonymous review, `[reviewer-name]` becomes "An anonymous user".

Step by step: [Control review emails and notifications](../how-to/control-review-emails-and-notifications.md).

## The activity stream

When **Activity Stream** is on, each published review adds one line to the BuddyPress activity stream, such as "Alex posted a new review to Sam", with the review text and star ratings.

- Anonymous reviews show "An anonymous user" with a plain default picture, and the name does not link to a profile.
- Editing or re-approving a review updates its existing entry. It does not post a duplicate.
- A review that is hidden, unpublished, trashed or deleted is removed from the stream. If it is published again, a fresh entry is created.
