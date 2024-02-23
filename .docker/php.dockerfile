FROM php:8.1-fpm-alpine

WORKDIR /app

ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN set -xe \
  && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
  && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS \
  && apk add --no-cache --update oniguruma-dev libpng-dev libwebp-dev jpeg-dev libjpeg-turbo-dev freetype-dev libmemcached-libs zlib mysql-client tzdata ca-certificates zip git bash \
  && update-ca-certificates \
  && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring opcache gd \
  && pecl install memcache \
  && docker-php-ext-enable memcache \
  && docker-php-source delete \
  && rm -rf /tmp/* /var/cache/* /usr/src/* \
  && ln -sf /app/vendor/bin/drush /usr/local/bin/drush \
  && apk del .memcached-deps .phpize-deps

COPY .docker/php.ini /usr/local/etc/php/conf.d/zzz-custom.ini

# Composer install all assets
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --profile --no-interaction && composer clear-cache

# Copy application files
COPY . .

