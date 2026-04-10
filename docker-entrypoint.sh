#!/bin/bash
set -e

echo "--- Fixing Apache MPM ---"
rm -f /etc/apache2/mods-enabled/mpm_event.load \
      /etc/apache2/mods-enabled/mpm_event.conf \
      /etc/apache2/mods-enabled/mpm_worker.load \
      /etc/apache2/mods-enabled/mpm_worker.conf
ls /etc/apache2/mods-enabled/mpm_* 2>/dev/null || echo "mpm_event/worker disabled"

echo "--- Caching config/routes/views ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Running migrations and seeding ---"
php artisan migrate:fresh --seed --force

echo "--- Starting Apache ---"
exec apache2-foreground
