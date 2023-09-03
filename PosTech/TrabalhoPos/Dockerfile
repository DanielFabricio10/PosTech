FROM php:8.2-apache

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

RUN echo "extension=pdo_mysql.so" >> /usr/local/etc/php/php.ini

RUN a2enmod rewrite