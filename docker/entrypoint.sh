#!/bin/sh
set -e

cd /var/www/html

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache database
chown -R www-data:www-data storage bootstrap/cache database

if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chown www-data:www-data database/database.sqlite
fi

# Keep the site up even if a migration fails; log and continue.
if ! php artisan migrate --force --no-interaction; then
    echo "WARNING: php artisan migrate failed" >&2
fi

if [ ! -e public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

php artisan sitemap:generate --no-interaction || true

php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

php artisan config:cache --no-interaction || true
php artisan route:cache --no-interaction || true
php artisan view:cache --no-interaction || true

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
