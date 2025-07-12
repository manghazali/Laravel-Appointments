#!/bin/bash
# Wait for MySQL to be ready
until mysqladmin ping -h"$DB_HOST" --silent; do
    echo "Waiting for database..."
    sleep 2
done

# Ensure .env exists
cp .env.example .env 2>/dev/null

# Generate key if not already present
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --seed --force

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
