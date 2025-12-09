# Stage 1: Build dependencies and autoloader
FROM composer:2 as builder

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies (ignoring scripts to avoid failures due to missing environment)
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# Copy application source
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative


# Stage 2: Runtime Application
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    zip \
    intl \
    opcache \
    mbstring \
    exif \
    pcntl \
    bcmath

# Setup Nginx
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Setup Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup Workdir
WORKDIR /var/www

# Copy application from builder
COPY --from=builder /app /var/www

# Ensure correct permissions for web server
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/data

# Expose port (Nginx)
EXPOSE 80

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
