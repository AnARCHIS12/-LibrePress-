#!/bin/sh
set -eu

php artisan down || true
php artisan librepress:backup || true
composer install --no-interaction --prefer-dist --no-dev
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
php artisan up

