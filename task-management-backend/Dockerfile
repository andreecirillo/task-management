# Base PHP image
FROM php:8.3-fpm as php

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# --- Final Image ---

FROM nginx:alpine

# Install PHP-FPM
RUN apk add --no-cache php8 php8-fpm php8-pdo_pgsql php8-opcache php8-session php8-tokenizer php8-mbstring php8-xml php8-ctype php8-curl php8-fileinfo php8-json php8-openssl php8-phar php8-zlib php8-pdo php8-pdo_pgsql

# Copy app
COPY --from=php /var/www /var/www

# Copy nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Set working directory
WORKDIR /var/www

# Expose default HTTP port
EXPOSE 8080

# Start Nginx
CMD ["nginx", "-g", "daemon off;"]
