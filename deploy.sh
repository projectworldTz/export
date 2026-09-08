#!/usr/bin/env bash

set -Eeuo pipefail

APP_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PHP_BIN="${PHP_BIN:-php}"
BRANCH="${DEPLOY_BRANCH:-main}"
REMOTE="${DEPLOY_REMOTE:-origin}"
LOCK_DIR="$APP_DIR/storage/framework/deploy.lock"
MAINTENANCE_ENABLED=0

cd "$APP_DIR"

log() {
    printf '\n\033[1;34m[deploy]\033[0m %s\n' "$1"
}

fail() {
    printf '\n\033[1;31m[deploy failed]\033[0m %s\n' "$1" >&2
    exit 1
}

cleanup() {
    exit_code=$?

    if [ "$MAINTENANCE_ENABLED" -eq 1 ]; then
        "$PHP_BIN" artisan up >/dev/null 2>&1 || true
    fi

    rmdir "$LOCK_DIR" >/dev/null 2>&1 || true

    if [ "$exit_code" -eq 0 ]; then
        printf '\n\033[1;32m[deploy complete]\033[0m Application is live.\n'
    else
        printf '\n\033[1;31m[deploy failed]\033[0m Application was restored from maintenance mode.\n' >&2
    fi
}

trap cleanup EXIT

command -v git >/dev/null 2>&1 || fail "Git is not available."
command -v "$PHP_BIN" >/dev/null 2>&1 || fail "PHP is not available."
command -v composer >/dev/null 2>&1 || fail "Composer is not available."
[ -f artisan ] || fail "Run this script from the Laravel project repository."
[ -f .env ] || fail "The production .env file is missing."

mkdir -p "$APP_DIR/storage/framework"
mkdir "$LOCK_DIR" 2>/dev/null || fail "Another deployment appears to be running."

if ! git diff-index --quiet HEAD --; then
    fail "Tracked files contain local changes. Commit or restore them before deployment."
fi

log "Enabling maintenance mode"
"$PHP_BIN" artisan down --retry=60 || true
MAINTENANCE_ENABLED=1

log "Pulling $REMOTE/$BRANCH"
git fetch "$REMOTE" "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only "$REMOTE" "$BRANCH"

log "Installing optimized backend dependencies"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

log "Clearing stale application caches"
"$PHP_BIN" artisan optimize:clear

log "Applying database migrations"
"$PHP_BIN" artisan migrate --force

log "Verifying public storage link"
mkdir -p "$APP_DIR/storage/app/public"
if [ ! -L "$APP_DIR/public/storage" ]; then
    if [ -e "$APP_DIR/public/storage" ]; then
        fail "public/storage exists but is not a symbolic link. Remove or relocate it manually."
    fi
    ln -s "$APP_DIR/storage/app/public" "$APP_DIR/public/storage"
fi

log "Applying writable directory permissions"
chmod -R ug+rwX "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

log "Building Laravel production caches"
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache
"$PHP_BIN" artisan event:cache

log "Restarting queue workers when available"
"$PHP_BIN" artisan queue:restart >/dev/null 2>&1 || true

log "Disabling maintenance mode"
"$PHP_BIN" artisan up
MAINTENANCE_ENABLED=0
