FROM php:8.1.0alpha3-fpm-alpine

RUN mkdir -p /var/www/api

WORKDIR /var/www/api

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

COPY composer.json /var/www/api
COPY composer.lock /var/www/api

RUN composer install --no-autoloader --no-scripts --prefer-dist --no-autoloader --no-progress --no-suggest
RUN mkdir -p /var/www/api/boot

COPY boot/Kernel.php /var/www/api/boot
COPY boot/Cache.php /var/www/api/boot
COPY etc /var/www/api/etc
COPY bin /var/www/api/bin

RUN mkdir -p var/cache
RUN mkdir -p var/logs
RUN mkdir -p var/sessions

RUN mkdir -p src
COPY src src

RUN mkdir -p \
 public \
 public/images/avatars \
 public/images/background \
 public/attachments

RUN rm -fr etc/parameters.yml
RUN composer install && \
    composer dump-autoload --optimize --classmap-authoritative

RUN find var -exec chown www-data: {} +

CMD ["bash", "start.sh"]
