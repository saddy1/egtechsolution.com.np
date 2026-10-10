#!/usr/bin/env bash
#
# Deploy this Laravel site on the cPanel server.
#
# Run it ON THE SERVER (cPanel > Terminal, or SSH) from the project folder:
#
#     bash deployment.sh                  # normal deploy
#     bash deployment.sh --seed-catalog   # also add the starter shop products and demo projects
#
# If the server needs a specific PHP version, name it:
#
#     PHP=/opt/cpanel/ea-php82/root/usr/bin/php bash deployment.sh
#
# It is safe to run again at any time.

set -euo pipefail

cd "$(dirname "$0")"

PHP="${PHP:-php}"
SEED_CATALOG=false
[ "${1:-}" = "--seed-catalog" ] && SEED_CATALOG=true

step() { printf '\n\033[1;34m==> %s\033[0m\n' "$1"; }
warn() { printf '\033[1;33m  ! %s\033[0m\n' "$1"; }
fail() { printf '\033[1;31mERROR: %s\033[0m\n' "$1" >&2; exit 1; }

artisan() { "$PHP" artisan "$@"; }

# Read one value from .env (without running the file).
env_value() { grep -E "^$1=" .env | tail -n 1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//' -e "s/^'//" -e "s/'$//"; }

# ── Checks ────────────────────────────────────────────────────────────────
step "Checking the server"

[ -f artisan ] || fail "Run this from the project folder (the one containing 'artisan')."
[ -f .env ]    || fail "No .env file. Copy .env.example to .env, fill in the database details, then run: $PHP artisan key:generate"

"$PHP" -r 'exit(version_compare(PHP_VERSION, "8.1.0", ">=") ? 0 : 1);' \
    || fail "PHP 8.1 or newer is required, found $("$PHP" -r 'echo PHP_VERSION;'). Set PHP=/path/to/php (see the top of this file)."
echo "  PHP $("$PHP" -r 'echo PHP_VERSION;')"

[ "$(env_value APP_ENV)" = "production" ] || warn "APP_ENV is '$(env_value APP_ENV)' — it should be 'production' on the live site."
[ "$(env_value APP_DEBUG)" = "false" ]    || warn "APP_DEBUG is not 'false' — visitors would see error details. Set APP_DEBUG=false in .env."

# ── Maintenance mode (always lifted again, even if a step fails) ──────────
step "Putting the site in maintenance mode"
artisan down --retry=30 || true
trap 'echo; artisan up || true' EXIT

# ── Code ──────────────────────────────────────────────────────────────────
if [ -d .git ]; then
    step "Pulling the latest code"
    git pull --ff-only
else
    step "Not a git checkout — using the files already uploaded"
fi

# ── PHP packages ──────────────────────────────────────────────────────────
step "Installing PHP packages"
if command -v composer >/dev/null 2>&1; then
    COMPOSER_ALLOW_SUPERUSER=1 "$PHP" "$(command -v composer)" install --no-dev --optimize-autoloader --no-interaction --no-scripts
elif [ -f composer.phar ]; then
    "$PHP" composer.phar install --no-dev --optimize-autoloader --no-interaction --no-scripts
elif [ -f vendor/autoload.php ]; then
    warn "Composer not found — keeping the existing vendor/ folder."
else
    fail "Composer not found and there is no vendor/ folder. Install Composer, or upload vendor/ from your computer."
fi

# Composer's own post-install scripts need proc_open, which many cPanel hosts disable,
# so they are skipped above and the one that matters is run here directly. The old
# package list is removed first: it may name dev packages that were just uninstalled.
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
artisan package:discover

# ── CSS / JS ──────────────────────────────────────────────────────────────
step "Building CSS and JavaScript"
if command -v npm >/dev/null 2>&1; then
    npm ci --no-audit --no-fund
    npm run build
elif [ -f public/build/manifest.json ]; then
    warn "npm is not installed here — using the public/build folder already on the server."
    warn "If the design looks out of date: run 'npm run build' on your computer and upload public/build."
else
    fail "npm is not installed and public/build is missing. Run 'npm run build' on your computer, upload the public/build folder, then run this script again."
fi

# ── Database ──────────────────────────────────────────────────────────────
if [ "$(env_value DB_CONNECTION)" = "mysql" ] && command -v mysqldump >/dev/null 2>&1; then
    step "Backing up the database"
    mkdir -p storage/backups
    BACKUP="storage/backups/$(env_value DB_DATABASE)-$(date +%Y%m%d-%H%M%S).sql.gz"
    if MYSQL_PWD="$(env_value DB_PASSWORD)" mysqldump --no-tablespaces --single-transaction \
            -h "$(env_value DB_HOST)" -P "$(env_value DB_PORT)" -u "$(env_value DB_USERNAME)" "$(env_value DB_DATABASE)" | gzip > "$BACKUP"; then
        echo "  Saved $BACKUP"
        # Keep the 10 newest backups.
        ls -1t storage/backups/*.sql.gz 2>/dev/null | tail -n +11 | xargs -r rm -f
    else
        rm -f "$BACKUP"
        fail "Database backup failed, so nothing was changed. Check the DB_ settings in .env."
    fi
else
    warn "Skipping the database backup (mysqldump not available). Take one in cPanel > phpMyAdmin if you need it."
fi

step "Updating the database"
artisan migrate --force

if $SEED_CATALOG; then
    if [ -f database/seeders/CatalogSeeder.php ]; then
        step "Adding starter shop products and demo projects"
        artisan db:seed --class=CatalogSeeder --force
        warn "The starter products have placeholder prices and stock — correct them in the admin panel."
    else
        warn "This site has no CatalogSeeder — nothing to seed."
    fi
fi

# ── Folders and caches ────────────────────────────────────────────────────
step "Preparing folders and caches"
mkdir -p public/uploads/products public/uploads/projects storage/framework/{cache/data,sessions,views} storage/logs
chmod -R u+rwX,g+rwX storage bootstrap/cache public/uploads

artisan optimize:clear
artisan config:cache
# Route caching is only a speed-up; some route files (duplicate route names) cannot be cached.
if ! artisan route:cache >/dev/null 2>&1; then
    artisan route:clear
    warn "Routes could not be cached (the site works normally without it)."
fi
artisan view:cache

# ── EG Tech link (only on the site that has it configured) ────────────────
if artisan list --raw 2>/dev/null | grep -q '^egtech:sync'; then
    if [ -n "$(env_value EGTECH_API_URL)" ] && [ -n "$(env_value EGTECH_API_TOKEN)" ]; then
        step "Importing products from EG Tech"
        artisan egtech:sync || warn "EG Tech import failed — the site is fine; it will be retried by the scheduler."
    else
        warn "EG Tech link is not set up: add EGTECH_API_URL and EGTECH_API_TOKEN to .env to import its products."
    fi
fi

# ── Done (the EXIT trap brings the site back up) ──────────────────────────
step "Deployed successfully"

if artisan list --raw 2>/dev/null | grep -q '^egtech:sync' && ! crontab -l 2>/dev/null | grep -q 'egtech:sync'; then
    warn "Add these two cron jobs in cPanel > Cron Jobs so EG Tech products and orders keep syncing:"
    echo "      */15 * * * * cd $(pwd) && $PHP artisan egtech:sync >> /dev/null 2>&1"
    echo "      */5 * * * * cd $(pwd) && $PHP artisan egtech:push-orders >> /dev/null 2>&1"
fi
