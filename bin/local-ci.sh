#!/usr/bin/env bash
# Local CI for buddypress-member-review: the blocking jobs of .github/workflows/ci.yml plus release checks. Exit 1 on any failure.
set -uo pipefail
cd "$(dirname "$0")/.."
fail=0
step() { printf '\n== %s\n' "$1"; }
bad() { echo "FAIL: $1"; fail=1; }

step "PHP lint"
while IFS= read -r f; do php -l "$f" >/dev/null || bad "lint $f"; done < <(git ls-files '*.php')

step "Version triangulation"
hv=$(grep -m1 -E '^\s*\*\s*Version:' buddypress-member-review.php | awk '{print $NF}')
cv=$(grep -m1 "define( 'BUPR_PLUGIN_VERSION'" buddypress-member-review.php | grep -oE "[0-9]+\.[0-9]+\.[0-9]+")
rv=$(grep -m1 -i 'Stable tag' readme.txt | tr -d '\r' | awk '{print $NF}')
pv=$(grep -m1 '"version"' package.json | grep -oE "[0-9]+\.[0-9]+\.[0-9]+")
echo "header=$hv const=$cv readme=$rv package=$pv"
[ "$hv" = "$cv" ] && [ "$cv" = "$rv" ] && [ "$rv" = "$pv" ] || bad "version mismatch"

step "Changelog entry"
grep -q "^= $hv" readme.txt || bad "readme.txt has no = $hv = changelog entry"

step "No em dashes in readme"
if grep -q '—' readme.txt; then bad "readme.txt contains an em dash"; fi

step "PHPStan"
if [ -x vendor/bin/phpstan ]; then
	vendor/bin/phpstan analyse --no-progress --memory-limit=1G >/dev/null || bad "phpstan"
else
	echo "skip: run composer install first"
fi

step "WPCS"
if command -v phpcs >/dev/null 2>&1; then
	phpcs -q --standard=phpcs.xml || bad "phpcs"
elif [ -x vendor/bin/phpcs ]; then
	vendor/bin/phpcs -q --standard=phpcs.xml || bad "phpcs"
else
	echo "skip: phpcs not installed"
fi

if [ $fail -eq 0 ]; then echo -e "\nALL CHECKS PASSED"; else echo -e "\nCHECKS FAILED"; fi
exit $fail
