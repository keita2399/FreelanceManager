#!/bin/bash
set -e

echo "--- Caching config/routes/views ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Running migrations and seeding ---"
php artisan migrate:fresh --seed --force

echo "--- Starting Apache ---"
exec apache2-foreground
