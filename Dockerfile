FROM php:8.4-apache
RUN apt -y update && apt -y upgrade
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite