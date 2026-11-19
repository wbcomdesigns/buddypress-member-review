#!/bin/bash
# Gate G2 HTTP half: guest AJAX, submission forgery / rating bounds, subscriber vs admin screens.
B=http://localhost:8907; W=~/work-artifacts/scratch/bin/w; cd "$(dirname "$0")"
fail=0; pass=0
chk(){ if [ "$2" = "$3" ]; then pass=$((pass+1)); echo "PASS $1 (got $3)"; else fail=$((fail+1)); echo "FAIL $1 expect=$2 got=$3"; fi; }
login(){ rm -f cj-$1.txt; curl -s -c cj-$1.txt -b cj-$1.txt -o /dev/null $B/wp-login.php; curl -s -c cj-$1.txt -b cj-$1.txt -o /dev/null -H "Cookie: wordpress_test_cookie=WP%20Cookie%20check" -d "log=$2&pwd=$3&wp-submit=Log+In&testcookie=1" $B/wp-login.php; }
login other mx_other pw; login target mx_target pw; login admin admin admin
OTHER=$($W user get mx_other --field=ID); TARGET=$($W user get mx_target --field=ID); AUTHOR=$($W user get mx_author --field=ID)
RN=$(curl -s -b cj-other.txt $B/members/mx_target/reviews/add-review/ | grep -o 'review_nonce":"[a-f0-9]*"' | head -1 | sed 's/.*:"//;s/"//')
[ -n "$RN" ] || { echo "no nonce"; exit 2; }
# H1 guests reach no AJAX action (wp_ajax_ only; nonce is irrelevant to a guest)
for a in bupr_approve_review allow_bupr_member_review_update bupr_edit_review bupr_update_review bupr_add_owner_reply bupr_flag_review bupr_save_criteria bupr_reset_criteria bupr_get_custom_criteria_values bupr_request_endorsement bupr_approve_endorsement bupr_decline_endorsement bupr_give_endorsement; do
  code=$(curl -s -o /tmp/g.out -w '%{http_code}' -d "action=$a&nonce=$RN&review_id=1&review=1" $B/wp-admin/admin-ajax.php)
  body=$(cat /tmp/g.out | head -c 40)
  if [ "$code" = 400 ] || [ "$body" = "0" ] || [ "$body" = "-1" ]; then chk "guest admin-ajax $a" blocked blocked; else chk "guest admin-ajax $a" blocked "code=$code body=$body"; fi
done
# H2 forged author: other posts as other with bupr_current_user=<author id>
before=$($W post list --post_type=review --post_status=any --format=count)
curl -s -b cj-other.txt -d "action=allow_bupr_member_review_update&bupr_member_id=$TARGET&bupr_current_user=$AUTHOR&bupr_review_desc=forged-http&bupr_review_rating[Member Response]=5&bupr_review_rating[Member Skills]=5&nonce=$RN" $B/wp-admin/admin-ajax.php -o /tmp/f.out
PA=$($W db query "SELECT post_author FROM wp_posts WHERE post_type='review' AND post_content='forged-http' ORDER BY ID DESC LIMIT 1" --skip-column-names)
chk "submit with forged bupr_current_user stores the session user as author" "$OTHER" "$PA"
# H3 self review by target
curl -s -b cj-target.txt -d "action=allow_bupr_member_review_update&bupr_member_id=$TARGET&bupr_review_desc=self-http&bupr_review_rating[Member Response]=5&bupr_review_rating[Member Skills]=5&nonce=$(curl -s -b cj-target.txt $B/members/mx_author/reviews/add-review/ | grep -o 'review_nonce":"[a-f0-9]*"' | head -1 | sed 's/.*:"//;s/"//')" $B/wp-admin/admin-ajax.php -o /tmp/s.out
n=$($W db query "SELECT COUNT(*) FROM wp_posts WHERE post_type='review' AND post_content='self-http'" --skip-column-names)
chk "self review is refused" 0 "$n"
# H4 rating bounds: a member posts rating 999 and -5
login rev3 mx_author pw
curl -s -b cj-rev3.txt -d "action=allow_bupr_member_review_update&bupr_member_id=$OTHER&bupr_review_desc=bounds-http&bupr_review_rating[Member Response]=999&bupr_review_rating[Member Skills]=-5&nonce=$(curl -s -b cj-rev3.txt $B/members/mx_other/reviews/add-review/ | grep -o 'review_nonce":"[a-f0-9]*"' | head -1 | sed 's/.*:"//;s/"//')" $B/wp-admin/admin-ajax.php -o /tmp/b.out
BID=$($W db query "SELECT ID FROM wp_posts WHERE post_type='review' AND post_content='bounds-http' ORDER BY ID DESC LIMIT 1" --skip-column-names)
if [ -n "$BID" ]; then V=$($W post meta get $BID profile_star_rating | tr -d '\n '); echo "  stored rating meta: $V"; case "$V" in *999*|*18446*) chk "rating values are clamped to 1-5" clamped "UNCLAMPED $V";; *) chk "rating values are clamped to 1-5" clamped clamped;; esac; else chk "out-of-range rating is rejected or stored clamped" ok "no review saved (rejected)"; fi
# H5 subscriber and guest versus admin screens
for u in "edit.php?post_type=review" "admin.php?page=bp-member-review-settings" "edit.php?post_type=review&page=bupr-review-management" "admin.php?page=wbcom-license-page" "admin.php?page=wbcomplugins"; do
  c=$(curl -s -o /dev/null -w '%{http_code}' -b cj-other.txt "$B/wp-admin/$u"); chk "subscriber blocked from wp-admin/$u" 403 "$c"
  c=$(curl -s -o /dev/null -w '%{http_code}' -b cj-admin.txt "$B/wp-admin/$u"); chk "admin reaches wp-admin/$u" 200 "$c"
done
# H6 admin-post without a login
c=$(curl -s -o /dev/null -w '%{http_code}' "$B/wp-admin/admin-post.php?action=bupr_dismiss_flag&review_id=1&_wpnonce=x"); echo "  guest admin-post dismiss -> $c (handler is admin_post_ only, nopriv not registered)"; chk "guest cannot reach admin_post_bupr_dismiss_flag" 400 "$c"
# H7 a subscriber cannot save plugin settings through options.php
c=$(curl -s -o /tmp/o.out -w '%{http_code}' -b cj-other.txt -d "option_page=bupr_admin_settings&action=update&_wpnonce=x" $B/wp-admin/options.php); chk "subscriber POST options.php refused" 403 "$c"
echo "HTTP TOTAL pass=$pass fail=$fail"
