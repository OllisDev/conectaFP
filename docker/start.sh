#!/bin/bash

cd /var/www

# Crear directorios necesarios
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views  
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Establecer permisos PRIMERO (antes de cualquier comando artisan)
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Eliminar archivos de caché
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
rm -rf storage/logs/*.log

# Crear .env con las variables de entorno de Render
cat > .env << EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"

DATABASE_URL="${DATABASE_URL}"

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
LOG_LEVEL=error
EOF

# Limpiar cachés con artisan (ahora con permisos correctos)
php artisan config:clear || echo "Config clear failed, continuing..."
php artisan route:clear || echo "Route clear failed, continuing..."
php artisan view:clear || echo "View clear failed, continuing..."
php artisan cache:clear || echo "Cache clear failed, continuing..."

# Función para esperar a PostgreSQL
wait_for_db() {
    echo "Esperando a que la base de datos esté disponible..."
    max_attempts=60
    attempt=0
    
    until php artisan db:show 2>&1 || [ $attempt -eq $max_attempts ]; do
        attempt=$((attempt + 1))
        echo "Intento $attempt de $max_attempts..."
        sleep 2
    done
    
    if [ $attempt -eq $max_attempts ]; then
        echo "ERROR: No se pudo conectar después de $max_attempts intentos"
        echo "Verificando .env:"
        cat .env | grep -v PASSWORD
        exit 1
    fi
    
    echo "✓ Conexión exitosa"
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