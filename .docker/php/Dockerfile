FROM php:7.4-apache

ARG PROXY=http://193.49.118.36:8080

RUN echo $http_proxy

ENV http_proxy=$PROXY
ENV https_proxy=$PROXY

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf



RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl

RUN pear config-set http_proxy $PROXY

RUN pecl install apcu && docker-php-ext-enable apcu

RUN apt-get install -y nodejs
RUN apt-get install -y npm && npm i -g corepack


RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www/lp-web

