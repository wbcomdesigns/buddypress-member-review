# Choose Who Can Write and Who Can Receive Reviews

Goal: limit reviews to the right kinds of members. For example, only "customers" can review, and only "sellers" can be reviewed.

## Steps

1. Go to **WB Plugins > Member Reviews** and open the **General** tab.
2. Find **Reviewer Roles** ("Select user roles that can submit reviews."). Click in the box to add a role. Click the small x on a role to remove it.
3. Find **Reviewee Roles** ("Select user roles that can receive reviews."). Choose the roles in the same way.
4. Click **Save Changes**.

Both boxes list every role on your site, including roles added by other plugins (for example Customer or Shop Manager).

## What the settings mean

| Setting | Effect |
|---|---|
| **Reviewer Roles** | Only members with one of these roles can write reviews. Other members do not get the **Add Review** tab or button. If they try anyway, the review is refused with "You are not allowed to review this member." |
| **Reviewee Roles** | Only members with one of these roles can receive reviews. On other members' profiles there is no **Add Review** tab or button. |

A member counts as allowed when any of their roles is on the list.

By default every role is allowed in both boxes. If you remove every role from a box and save, the plugin treats it as "everyone" again, so that box can never lock all members out.

## Example

Only customers may review, and only sellers may be reviewed:

- **Reviewer Roles**: Customer
- **Reviewee Roles**: Seller

Alex (Customer) opens Sam's profile (Seller) and sees **Add Review**. Alex opens another customer's profile and does not.

## What members see

- Members who may not review see no **Add Review** tab or button.
- The **Reviews** tab itself still shows on every profile, with the reviews already received.
- Existing reviews are not removed when you change roles.

## Tips

- Administrators are not exempt. If **Administrator** is not in **Reviewer Roles**, an administrator cannot write reviews either.
- The **Add Review** button in the profile header (if you switched on **Show Review Button**) looks only at the first role of each of the two members. If someone has several roles and the button is missing, put their main role on the lists, or use the **Add Review** tab under **Reviews**, which accepts any role.
- Changing roles takes effect immediately. There is nothing to clear.

## If it does not work

See "I cannot see Add Review" in [Troubleshooting](../troubleshooting/troubleshooting.md).
