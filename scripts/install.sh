#!/bin/sh
set -eu

composer install --no-interaction --prefer-dist
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan optimize

