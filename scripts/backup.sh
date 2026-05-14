#!/bin/sh
set -eu

php artisan librepress:backup
php artisan librepress:export

