#!/bin/sh

chown -R www-data: /var/www/html/storage/logs

cd /var/www/html

echo "Starting Migration..."
php artisan migrate --force

echo "Creating caches..."
php artisan route:cache
php artisan config:cache
php artisan view:cache

php-fpm -D && nginx -g 'daemon off;'
