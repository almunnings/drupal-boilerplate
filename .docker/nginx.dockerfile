ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM uselagoon/nginx-drupal:latest

ENV WEBROOT web
ENV BASIC_AUTH off

COPY --from=cli /app /app
