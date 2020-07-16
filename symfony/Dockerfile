FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . /var/www/
RUN composer install

EXPOSE 9000
