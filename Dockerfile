# Multi-stage build para optimizar la imagen final
FROM php:8.1-fpm-alpine as php-builder

# Instalar composer y dependencias de build
RUN apk add --no-cache \
    composer \
    git \
    curl \
    && rm -rf /var/cache/apk/*

# Copiar código de la aplicación
COPY . /app

WORKDIR /app

# Instalar dependencias de composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage final: nginx + php-fpm
FROM php:8.1-fpm-alpine

# Instalar nginx
RUN apk add --no-cache \
    nginx \
    && mkdir -p /var/log/nginx /var/run/nginx

# Copiar aplicación del builder
COPY --from=php-builder /app /app

# Crear archivo de configuración nginx para Laminas
RUN mkdir -p /etc/nginx/http.d && \
    echo 'server {
    listen 80;
    server_name _;
    root /app/public;
    index index.php;
    
    # Configurar logs
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log warn;
    
    # Rewrite rules para Laminas
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Procesar archivos PHP
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Negar acceso a archivos de sistema
    location ~ /\. {
        deny all;
    }
    
    location ~ \.env {
        deny all;
    }
    
    location ~ \.htaccess {
        deny all;
    }
}' > /etc/nginx/http.d/default.conf

WORKDIR /app

# Exponer puerto 80
EXPOSE 80

# Script de inicio para ejecutar php-fpm y nginx
RUN echo '#!/bin/sh
echo "Starting PHP-FPM..."
php-fpm -D
echo "Starting Nginx..."
nginx -g "daemon off;"' > /entrypoint.sh && chmod +x /entrypoint.sh

# Comando por defecto
CMD ["/entrypoint.sh"]
