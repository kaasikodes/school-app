#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi


echo "Creating env file for env $APP_ENV"
cp  .env.local .env


php artisan migrate
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan route:clear

php artisan serve --host=0.0.0.0 --port=8000 --env=.env
exec docker-php-entrypoint "$@"