#!/bin/bash

cd /var/www

# Crear directorios necesarios
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views  
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Primero: Eliminar TODA la caché existente
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# Segundo: Crear .env con las variables de entorno de Render
cat > .env << EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"

DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
LOG_LEVEL=error
EOF

# Establecer permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Tercero: Limpiar cachés con artisan
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Función para esperar a PostgreSQL
wait_for_db() {
    echo "Esperando a que la base de datos esté disponible..."
    max_attempts=30
    attempt=0
    
    until php artisan db:show > /dev/null 2>&1 || [ $attempt -eq $max_attempts ]; do
        attempt=$((attempt + 1))
        echo "Intento $attempt de $max_attempts..."
        sleep 2
    done
    
    if [ $attempt -eq $max_attempts ]; then
        echo "ERROR: No se pudo conectar a la base de datos después de $max_attempts intentos"
        exit 1
    fi
    
    echo "✓ Conexión a base de datos exitosa"
}

# Esperar a que la base de datos esté lista
wait_for_db

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Cachear configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Iniciar Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g 'daemon off;'