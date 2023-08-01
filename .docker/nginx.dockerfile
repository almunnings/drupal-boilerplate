ARG PHP_IMAGE
FROM ${PHP_IMAGE} as php

FROM nginx:1-alpine

COPY .docker/nginx.conf /etc/nginx/conf.d/default.conf

COPY --from=php /app /app
