FROM php:7.4-cli

# Set working directory
WORKDIR /var/www

# Install system dependencies

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev zip postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring exif pcntl


# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

# Expose port and run Laravel
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
