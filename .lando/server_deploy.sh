#!/bin/sh
set -e

# Update codebase
echo "Updating repository..."

git fetch origin staging
git reset --hard origin/staging

#Deploy
echo "Deploying application ..."
cd core

# Enter maintenance mode
php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.'

# Fix folder permissions
chown -R $USER:www-data storage bootstrap/cache

# Install dependencies based on lock file
composer install --no-interaction --prefer-dist --optimize-autoloader

# Migrate database
php artisan migrate --force

# Note: If you're using queue workers, this is the place to restart them.
# ...

# Clear cache
php artisan optimize

# Reload PHP to update opcache
echo "Reloading PHP Service" | sudo -S service php7.4-fpm reload


# Exit maintenance mode
php artisan up

echo "Application deployed!"
