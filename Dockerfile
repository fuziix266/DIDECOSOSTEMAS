# Usamos PHP 8.3 con Apache (Más simple que Nginx para empezar)
FROM php:8.3-apache

# 1. Instalar dependencias del sistema y librerías gráficas (gd, intl, zip)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Configurar e instalar extensiones PHP
# Laminas necesita SI o SI: intl y zip. Añadimos pdo_mysql y gd por si acaso.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo_mysql zip gd opcache

# 3. Activar mod_rewrite (Vital para las rutas de Laminas)
RUN a2enmod rewrite

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar Apache para que apunte a la carpeta /public
# Esto hace la magia: cambia la configuración de Apache sin necesidad de archivos externos
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Preparar directorio de trabajo
WORKDIR /var/www/html

# 7. Copiar los archivos del proyecto
COPY . .

# 8. Instalar dependencias de PHP (Vendor)
RUN composer install --no-dev --optimize-autoloader

# 9. Permisos (Importante para que Laminas pueda escribir en data/cache)
# Creamos la carpeta data por si no existe y damos permisos
RUN mkdir -p data/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/data

EXPOSE 80
