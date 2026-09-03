#!/usr/bin/env bash
#
# Builds the documentation of every supported major version into a single site.
#
# The current major is built from the working tree and lands at the root, the
# older ones are built from their last release tag and land under a version
# folder. Every older major keeps the VitePress release it was written for,
# which is why each one gets its own dependency install.
#
# Usage: docs/build-versions.sh [output-directory]
#
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT="${1:-$REPO_ROOT/docs/.vitepress/dist}"
BASE='/Laravel-AdminLTE'
SITE_URL='https://jeroennoten.github.io/Laravel-AdminLTE'

# The older majors to publish, as "<folder>=<git tag>" pairs.
LEGACY_VERSIONS=(
    "v3=v3.16.0"
)

echo "==> Building the current documentation"
cd "$REPO_ROOT"
npm run docs:build

if [ "$OUT" != "$REPO_ROOT/docs/.vitepress/dist" ]; then
    rm -rf "$OUT"
    mkdir -p "$(dirname "$OUT")"
    cp -R "$REPO_ROOT/docs/.vitepress/dist" "$OUT"
fi

for entry in "${LEGACY_VERSIONS[@]}"; do
    folder="${entry%%=*}"
    tag="${entry#*=}"
    work="$(mktemp -d)"

    echo "==> Building the $folder documentation from $tag"

    # A worktree rather than 'git archive': the archive honours the
    # 'export-ignore' of .gitattributes, which excludes the whole docs folder.
    git worktree add --detach --quiet "$work" "$tag"

    config="$work/docs/.vitepress/config.js"

    # Move the site under its version folder. The favicon of the head block is
    # written out verbatim by VitePress, so it carries the base by hand and has
    # to be rewritten too.
    perl -pi -e "s#base: '$BASE',#base: '$BASE/$folder/',#" "$config"
    perl -pi -e "s#href: '$BASE/imgs/#href: '$BASE/$folder/imgs/#g" "$config"

    # Tell the reader they are on an older version, and give them the way out.
    # VitePress prepends the base to every nav link that starts with a slash,
    # and here the base already is the version folder, so the way back to the
    # current documentation has to be an absolute url.
    perl -0pi -e "s#(themeConfig:\s*\{)#\$1\n        nav: [\n            {\n                text: '$folder',\n                items: [\n                    { text: 'v4 (current)', link: '$SITE_URL/', target: '_self', noIcon: true },\n                    { text: '$folder', link: '/' },\n                ],\n            },\n        ],#" "$config"

    (
        cd "$work"
        npm ci --silent --no-audit --no-fund
        npm run docs:build
    )

    mkdir -p "$OUT/$folder"
    cp -R "$work/docs/.vitepress/dist/." "$OUT/$folder/"

    git worktree remove --force "$work"
done

echo "==> Done. The combined site is at: $OUT"
