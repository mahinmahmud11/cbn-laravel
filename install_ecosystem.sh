#!/bin/bash
cd /var/www/html
php artisan filament:install --panels -n
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
