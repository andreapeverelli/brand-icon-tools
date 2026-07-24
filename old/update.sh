#!/usr/bin/env bash

echo "Fetching releases..."
git fetch --tags origin

LATEST_TAG=$(git tag --sort=-v:refname | head -n1)

if [[ -z "$LATEST_TAG" ]]; then
    echo "No release tags found."
    exit 1
fi

echo "Latest release: $LATEST_TAG"

git switch --detach "$LATEST_TAG"

echo "Installing..."

install -m 755 brand-icon-tools /usr/local/bin/brand-icon-tools

