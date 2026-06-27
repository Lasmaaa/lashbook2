#!/bin/bash
set -euo pipefail

PORT="${PORT:-8080}"

log() {
  echo "[lashbook2-start] $*"
}

if [ ! -f .env ]; then
  cp .env.example .env
fi

php docker/sync-env.php

if [ -f bootstrap/cache/render-env.sh ]; then
  # shellcheck disable=SC1091
  . bootstrap/cache/render-env.sh
fi

if [ -n "${DATABASE_URL:-}" ]; then
  export DB_URL="${DATABASE_URL}"
fi

TARGET_CACHE_STORE="${CACHE_STORE:-database}"
TARGET_SESSION_DRIVER="${SESSION_DRIVER:-database}"

# Database cache/session need migrated tables; use file drivers during bootstrap.
export CACHE_STORE=file
export SESSION_DRIVER=file

rm -f bootstrap/cache/config.php bootstrap/cache/routes-v7.php bootstrap/cache/routes.php bootstrap/cache/events.php

php artisan optimize:clear --no-interaction 2>/dev/null || true

attempt=1
max_attempts=30
until php artisan migrate --force --no-interaction; do
  if [ "$attempt" -ge "$max_attempts" ]; then
    log "Database migration failed after ${max_attempts} attempts"
    exit 1
  fi
  log "Waiting for database (${attempt}/${max_attempts})..."
  attempt=$((attempt + 1))
  sleep 2
done

log "Migrations completed"

php artisan db:seed --force --no-interaction || log "Seeding skipped or already applied"

export CACHE_STORE="$TARGET_CACHE_STORE"
export SESSION_DRIVER="$TARGET_SESSION_DRIVER"

php docker/sync-env.php

php artisan package:discover --ansi
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-enabled/000-default.conf

log "Starting Apache on port ${PORT}"
exec apache2-foreground
