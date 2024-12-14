#!/bin/sh

if [ ! -f /app/database/sqlite/servas.db ]; then
    touch /app/database/sqlite/servas.db
fi

cd /app

echo "Starting Migration..."
php artisan migrate --force

echo "Creating caches..."
php artisan config:cache
php artisan view:cache

frankenphp run --config /etc/caddy/Caddyfile
