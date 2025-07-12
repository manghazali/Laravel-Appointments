FROM php:7.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev zip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel files
COPY . .

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

CMD php artisan serve --host=0.0.0.0 --port=8000
