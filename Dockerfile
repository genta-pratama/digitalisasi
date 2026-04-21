FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    caddy \
    nodejs \
    npm \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip gd pdo pdo_mysql opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-scripts --no-interaction

RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN echo ':80 { \n\
    root * /var/www/html/public \n\
    php_fastcgi 127.0.0.1:9000 \n\
    file_server \n\
    encode gzip \n\
}' > /etc/caddy/Caddyfile

EXPOSE 80

CMD caddy start --config /etc/caddy/Caddyfile && php-fpm