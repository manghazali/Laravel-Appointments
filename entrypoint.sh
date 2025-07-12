#!/bin/bash

# Ensure .env exists
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate app key if not already set
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=" .env | grep -q 'APP_KEY=$'; then
  php artisan key:generate --force
fi

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --seed --force

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
