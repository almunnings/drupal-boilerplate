FROM php:8.1-fpm-alpine

WORKDIR /app

RUN \
  apk add --no-cache oniguruma-dev libpng-dev libwebp-dev jpeg-dev libjpeg-turbo-dev freetype-dev mysql-client tzdata zip git bash tini \
  && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring opcache gd \
  && docker-php-source delete \
  && rm -rf /tmp/* /var/cache/* /usr/src/* \
  && echo "0 * * * * /app/vendor/bin/drush cron > /proc/1/fd/1 2>/proc/1/fd/2" > /var/spool/cron/crontabs/root \
  && ln -sf /app/vendor/bin/drush /usr/local/bin/drush

COPY .docker/php.ini /usr/local/etc/php/conf.d/zzz-custom.ini

# Composer install all assets
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --profile --no-interaction && composer clear-cache

# Copy application files
COPY . .

ENTRYPOINT ["/sbin/tini", "--"]
CMD ["/usr/sbin/crond", "-f", "-d", "8"]
