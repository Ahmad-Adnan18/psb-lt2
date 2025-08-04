# Gunakan image PHP resmi
FROM php:8.2-fpm

# Install dependencies system
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy semua file ke container
COPY . .

# Salin file .env.example jadi .env (Railway butuh ini untuk key generate)
RUN cp .env.example .env

# Install dependency Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate app key
RUN php artisan key:generate

# (Opsional) Jalankan migrate di awal
# RUN php artisan migrate --force

# Expose port Laravel
EXPOSE 8000

# Jalankan Laravel saat container start
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
