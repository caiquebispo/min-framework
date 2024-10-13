FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

WORKDIR /var/www/html

COPY ../.. .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer