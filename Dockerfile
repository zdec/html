# Laravel 12 requiere PHP 8.4+
FROM php:8.4-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP (solo las que no vienen en la imagen base: pdo_pgsql, gd)
# mbstring, xml, ctype, json, bcmath, fileinfo, zip, opcache suelen estar preinstaladas
RUN docker-php-ext-install pdo_pgsql

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
