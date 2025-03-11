#!/bin/sh

if [ ! -f /app/database/database.sqlite ]; then
    touch /app/database/database.sqlite
fi

cd /app

echo "Starting Migration..."
php artisan migrate --force

echo "Creating caches..."
php artisan config:cache
php artisan view:cache

frankenphp run --config /etc/caddy/Caddyfile
