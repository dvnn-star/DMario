# --- STAGE 1: Build Frontend Assets (Node.js + Vite) ---
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# --- STAGE 2: PHP Runtime (Laravel) ---
FROM php:8.2-cli-alpine

# Install ekstensi PHP yang dibutuhkan Laravel & MySQL
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath gd intl

# Ambil Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy seluruh source code project
COPY . .

# Copy hasil build Vite dari STAGE 1
COPY --from=frontend /app/public/build ./public/build

# Install dependency PHP tanpa dev-packages
RUN composer install --no-dev --optimize-autoloader

# Set permission storage
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 10000

# Perintah saat container menyala
CMD php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}