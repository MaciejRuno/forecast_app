# ./docker/php/Dockerfile
FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

WORKDIR /var/www

COPY . /var/www