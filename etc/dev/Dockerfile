ARG PHP_VERSION=8.1.2-fpm-alpine


FROM php:${PHP_VERSION} as php-dev

RUN apk update
RUN apk add bash
RUN apk add curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

ENV APP_ENV dev
ENV APP_SECRET default-secret

EXPOSE 9000

FROM nginx:alpine as nginx

ENV APP_ENV dev

WORKDIR /var/www

COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/sites/ /etc/nginx/sites-available
COPY nginx/conf.d/ /etc/nginx/conf.d

CMD ["nginx"]
EXPOSE 80
