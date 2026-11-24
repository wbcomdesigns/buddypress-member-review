#!/usr/bin/env bash
# Build dist/bp-user-profile-reviews-<version>.zip (folder = WordPress.org slug) from the committed tree (git archive) minus .distignore,
# so the zip always equals the repo. Runs the local CI first.
set -euo pipefail
cd "$(dirname "$0")/.."
slug=bp-user-profile-reviews
main=buddypress-member-review.php
bin/local-ci.sh
version=$(grep -m1 -E '^\s*\*\s*Version:' "$main" | awk '{print $NF}')
work=$(mktemp -d)
trap 'rm -rf "$work"' EXIT
mkdir -p "$work/$slug" dist
git archive HEAD | tar -x -C "$work/$slug"
while IFS= read -r rule; do
	rule=${rule#/}
	[ -z "$rule" ] && continue
	# shellcheck disable=SC2086
	( cd "$work/$slug" && rm -rf -- $rule )
done < .distignore
out="$PWD/dist/$slug-$version.zip"
rm -f "$out"
# Deflate every entry: a PHP file stored uncompressed trips the store's upload firewall.
( cd "$work" && python3 -I -c 'import os,sys,zipfile
out,top=sys.argv[1],sys.argv[2]
with zipfile.ZipFile(out,"w",zipfile.ZIP_DEFLATED,compresslevel=9) as z:
    for r,d,f in os.walk(top):
        d.sort()
        for n in sorted(f): z.write(os.path.join(r,n))' "$out" "$slug" )
zip="$out"
# --- WordPress.org folder lock (Wbcom standard - do not edit) ----------------------
entries=$(unzip -Z1 "$zip")   # read the whole list once: no early-exit pipe, safe under pipefail
top=${entries%%/*}
[ "$top" = "$slug" ] || { echo "Zip folder '$top' is not the WordPress.org slug '$slug'"; exit 1; }
grep -qx "$slug/$main" <<<"$entries" || { echo "Main file $slug/$main missing from zip"; exit 1; }
[ "$(LC_ALL=C grep -a -c '<?php' "$zip" || true)" = 0 ] || { echo "Zip has plain-text PHP (upload firewall)"; exit 1; }
# -----------------------------------------------------------------------------------
leaks=$(unzip -l "$out" | grep -iE '^ *[0-9]+ +[0-9-]+ +[0-9:]+ +[^/]+/(audit|bin|docs|tests|vendor|node_modules)/|CLAUDE|\.md$|\.map$' || true)
[ -z "$leaks" ] || { echo "Zip contains dev files:"; echo "$leaks"; exit 1; }
echo "Built $out ($(unzip -l "$out" | tail -1 | awk '{print $2}') files)"
