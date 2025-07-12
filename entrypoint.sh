#!/bin/bash
until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" --silent; do
  echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
  sleep 2
done

# Create the database if it doesn't exist
echo "Creating database $DB_DATABASE if it doesn't exist..."
mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\`;"

cp .env.example .env
php artisan key:generate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --seed --force

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
