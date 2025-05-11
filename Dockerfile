FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader


EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]