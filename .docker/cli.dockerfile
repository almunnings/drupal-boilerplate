FROM uselagoon/php-8.2-cli:latest

ENV WEBROOT web
ENV PATH "/app/vendor/bin:${PATH}"

COPY ./composer.* /app/
RUN composer install --no-dev --no-interaction --optimize-autoloader

COPY . /app
