#!/bin/sh
set -e
cd /var/www

# Install dependencies if vendor is missing
if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist
fi

# Prepare .env
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --force
fi

# Wait for DB and run migrations
for i in 1 2 3 4 5 6 7 8 9 10; do
  if php artisan migrate --force 2>/dev/null; then
    break
  fi
  [ $i -eq 10 ] && exit 1
  sleep 2
done

# Build frontend if manifest missing (e.g. after volume mount overwrote public/build)
if [ ! -f public/build/manifest.json ]; then
  npm install && npm run build
fi

# Ensure storage is writable
chown -R www:www storage bootstrap/cache 2>/dev/null || true

exec php-fpm
