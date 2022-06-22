#!/bin/sh

if [ ! -f /var/www/html/database/sqlite/servas.db ]; then
    touch /var/www/html/database/sqlite/servas.db
fi

chown -R www-data: /var/www/html/database/sqlite

cd /var/www/html

echo "Starting Migration..."
php artisan migrate --force

echo "Creating caches..."
php artisan config:cache
php artisan view:cache

php-fpm -D && nginx -g 'daemon off;'
