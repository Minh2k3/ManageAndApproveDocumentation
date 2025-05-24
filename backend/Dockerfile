FROM php:8.2-fpm

# Cài các extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif bcmath gd zip

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Làm việc tại thư mục project
WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader --no-dev \
    && php artisan config:cache \
    && php artisan route:cache
