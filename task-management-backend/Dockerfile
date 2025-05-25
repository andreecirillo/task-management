FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Copy .env.production as .env
COPY .env.production .env

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy NGINX configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose default port for Cloud Run
EXPOSE 8080

# Add explicit execution permission for sh (best practice but optional here)
RUN chmod +x /usr/bin/sh

# Start PHP-FPM and NGINX together
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
