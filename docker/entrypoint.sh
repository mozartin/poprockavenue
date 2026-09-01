#!/bin/sh
set -e

cd /var/www/html

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache database
chown -R www-data:www-data storage bootstrap/cache database

if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chown www-data:www-data database/database.sqlite
fi

php artisan migrate --force --no-interaction

if [ ! -e public/storage ]; then
    php artisan storage:link --no-interaction
fi

php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
