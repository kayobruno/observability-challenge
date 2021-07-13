#!/bin/bash
## Comandos necessários para instalação da aplicação
composer install
php artisan migrate
php artisan alerts:import

php-fpm

tail -f storage/logs/laravel.log