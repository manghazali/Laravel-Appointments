#!/bin/bash


# Create the database if it doesn't exist
echo "Creating database $DB_DATABASE if it doesn't exist..."
psql -h"$DB_HOST" -p"$DB_PORT" -U"$DB_USERNAME" -d postgres -c "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\`;"

cp .env.example .env
php artisan key:generate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --seed --force

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
