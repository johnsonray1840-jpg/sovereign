#!/bin/sh
set -e

# Run storage link if not present
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link || true
fi

# Ensure storage directories exist and are writable
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/untranslated
rm -rf /var/www/html/storage/logs/*.log || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Create dummy .env if missing in container (Render provides env vars into the process)
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# Cache configuration, routes, and views for production performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations automatically if DB is connected
php artisan migrate --force || true

# Configure dynamic port for Render (default to 80 if PORT is not set)
export PORT="${PORT:-80}"
sed -i "s/\${PORT}/$PORT/g" /etc/nginx/nginx.conf

# Start PHP-FPM in background (logging to stderr so Render logs capture PHP errors)
php-fpm -D -R

# Start a persistent queue worker in the background for asynchronous jobs
(while true; do php /var/www/html/artisan queue:work --stop-when-empty --tries=3 --timeout=60 || true; sleep 5; done) &

# Start Nginx in foreground
exec nginx -g "daemon off;"
