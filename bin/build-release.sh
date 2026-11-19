#!/usr/bin/env bash
# Build release-zip/buddypress-ads-<version>.zip from the committed tree (git archive) minus .distignore,
# so the zip always equals the repo. Runs the local CI first.
set -euo pipefail
cd "$(dirname "$0")/.."
slug=buddypress-member-review
bin/local-ci.sh
version=$(grep -m1 -E '^\s*\*\s*Version:' buddypress-member-review.php | awk '{print $NF}')
work=$(mktemp -d)
trap 'rm -rf "$work"' EXIT
mkdir -p "$work/$slug" release-zip
git archive HEAD | tar -x -C "$work/$slug"
while IFS= read -r rule; do
	rule=${rule#/}
	[ -z "$rule" ] && continue
	# shellcheck disable=SC2086
	( cd "$work/$slug" && rm -rf -- $rule )
done < .distignore
out="$PWD/release-zip/$slug-$version.zip"
rm -f "$out"
( cd "$work" && zip -qr "$out" "$slug" )
leaks=$(unzip -l "$out" | grep -iE '^ *[0-9]+ +[0-9-]+ +[0-9:]+ +[^/]+/(audit|bin|docs|tests|vendor|node_modules)/|CLAUDE|\.md$|\.map$' || true)
[ -z "$leaks" ] || { echo "Zip contains dev files:"; echo "$leaks"; exit 1; }
echo "Built $out ($(unzip -l "$out" | tail -1 | awk '{print $2}') files)"
