#!/bin/bash
set -e

cd /ai/proyek

export PATH="/root/.local/bin:/tools/node/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"
export HOME=/root

git pull origin main
composer install --no-dev --no-interaction --prefer-dist --no-progress
php artisan migrate --force
if [ -f package.json ]; then
  npm install --no-progress --ignore-scripts 2>/dev/null || true
  npm run build 2>/dev/null || true
fi
php artisan storage:link 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache
setsid service php8.3-fpm restart </dev/null >/dev/null 2>&1 &
echo "DEPLOY OK: $(git rev-parse --short HEAD)"
