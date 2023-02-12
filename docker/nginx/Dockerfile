ARG source_image=scratch
FROM nginx:stable-alpine AS base

COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod 775 /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

FROM ${source_image} AS source

FROM base AS test

COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY ./app/public /var/www/symfony/public

FROM base AS prod

COPY --from=source /var/www/symfony/public /var/www/symfony/public
