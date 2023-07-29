ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM php:8.1-fpm-alpine

RUN set -eux; apk add --no-cache zip oniguruma-dev libpng-dev libwebp-dev jpeg-dev libjpeg-turbo-dev freetype-dev mysql-client tzdata \
  && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring opcache gd \
  && docker-php-source delete

COPY .docker/php.ini /usr/local/etc/php/conf.d/zzz-custom.ini

COPY --from=cli /app /app
