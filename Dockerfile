FROM php:8.2-fpm

# Install dependency sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    nodejs \
    npm \
    && docker-php-ext-install intl zip gd mbstring bcmath pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project
COPY . .

# Install Laravel dependency
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install frontend dependency (kalau pakai Vite)
RUN npm install
RUN npm run build

# Expose port
EXPOSE 8000

# Run Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000