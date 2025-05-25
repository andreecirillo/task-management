FROM php:8.3-apache  

# Enable Apache mod_rewrite  
RUN a2enmod rewrite  

# Install system dependencies  
RUN apt-get update && apt-get install -y \  
    libpq-dev \  
    git \  
    unzip \  
    && docker-php-ext-install pdo pdo_pgsql  

# Install Composer  
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer  

# Set working directory  
WORKDIR /var/www  

# Copy project files  
COPY . .  

# Install PHP dependencies  
RUN composer install --no-interaction --prefer-dist --optimize-autoloader  

# Set proper permissions  
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache  

# Copy custom Apache configuration  
COPY apache.conf /etc/apache2/sites-available/000-default.conf  
