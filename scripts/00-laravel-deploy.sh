#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Clear config..."
php artisan config:clear

echo "Clear routes..."
php artisan route:clear

echo "Clear caches..."
php artisan cache:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
# to run new migrations - should be this by default
# php artisan migrate

# to empty the db and run migrations from beginning (force is done to ignore the prompt that asks 4 confirmation)
php artisan migrate:refresh --force

