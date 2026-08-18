FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/framework/testing storage/logs \
    && composer install --optimize-autoloader --no-interaction \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker-entrypoint.sh

EXPOSE 8000

CMD ["./docker-entrypoint.sh"]
