#!/bin/bash

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Ejecutar migraciones
cd /var/www
php artisan migrate --force

# Iniciar Nginx en primer plano
nginx -g 'daemon off;'