FROM php:8.1-cli-alpine

WORKDIR /app

RUN set -eux; apk add --no-cache zip oniguruma-dev libpng-dev libwebp-dev jpeg-dev libjpeg-turbo-dev freetype-dev mysql-client tzdata \
  git bash tini \
  && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring opcache gd \
  && docker-php-source delete

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY .docker/crontab.txt /var/spool/cron/crontabs/root
COPY .docker/php.ini /usr/local/etc/php/conf.d/zzz-custom.ini

COPY composer.json composer.lock ./
RUN composer install --no-dev --profile --no-interaction

COPY . .

RUN mkdir -p -v -m2775 ./web/sites/default/files

ENTRYPOINT ["/sbin/tini", "--"]
CMD ["/usr/sbin/crond", "-f", "-d", "8"]
