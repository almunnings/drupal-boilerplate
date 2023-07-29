ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM nginx:1-alpine

COPY .docker/nginx.conf /etc/nginx/conf.d/default.conf

COPY --from=cli /app /app
