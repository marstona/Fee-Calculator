FROM php:8.2-fpm-buster

RUN apt-get update && apt-get install -y \
 git \
 curl \
 libpng-dev \
 libonig-dev \
 libxml2-dev \
 zip \
 unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony

COPY composer.json composer.json
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader
