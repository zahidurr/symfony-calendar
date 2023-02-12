FROM public.ecr.aws/p1f0g9j3/lexly-main-base-app/php:8.0-fpm as base

RUN apt-get update && apt-get install -y lsb-base \
    libzip-dev \
    zip \
    git \
    gettext-base \
    libpq-dev \
    python3 \
    python3-pip

RUN pip3 install boto3

RUN docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_pgsql
RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/*

EXPOSE 9003
RUN mkdir -p /var/www/symfony && chown www-data:www-data /var/www/symfony
WORKDIR /var/www/symfony
FROM base as builder

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=2.1.6 &&  mkdir /var/www/.composer && chown www-data:www-data  /var/www/.composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

FROM builder as dev

ARG CLIENT_HOST

RUN apt-get update \
    && apt-get install -y fish \
    && chsh -s `which fish`

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=default" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.cli_color=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=${CLIENT_HOST}" >> /usr/local/etc/php/conf.d/xdebug.ini


RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/*

FROM builder as linux

ARG CLIENT_HOST

RUN groupadd -g 1000 app
RUN usermod -u 1000 -g 1000 www-data

RUN apt-get update \
    && apt-get install -y fish \
    && chsh -s `which fish`

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=default" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.cli_color=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=${CLIENT_HOST}" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/*

FROM builder as source

USER root
ARG SSH_PRIVATE_KEY
RUN mkdir /var/www/.ssh/
RUN echo "${SSH_PRIVATE_KEY}" > /var/www/.ssh/id_rsa
RUN touch /var/www/.ssh/known_hosts
RUN ssh-keyscan bitbucket.org >> /var/www/.ssh/known_hosts
RUN chown -R www-data:www-data /var/www/.ssh/
RUN chmod 400 /var/www/.ssh/id_rsa

USER www-data

COPY --chown=www-data:www-data ./app/*.json ./app/*.lock ./

RUN composer check-platform-reqs \
    && composer install \
        --prefer-dist \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts

COPY --chown=www-data:www-data ./app/bin ./bin
COPY --chown=www-data:www-data ./app/public ./public
COPY --chown=www-data:www-data ./app/translations ./translations
COPY --chown=www-data:www-data ./app/config ./config
COPY --chown=www-data:www-data ./app/src ./src
COPY --chown=www-data:www-data ./app/templates ./templates
#COPY --chown=www-data:www-data ./app/migrations ./migrations
RUN mkdir -p /var/www/symfony/var

COPY --chown=www-data:www-data ./app/.env .env
RUN chmod +x bin/console
RUN mkdir -p public/api-checkout && bin/console assets:install public/api-checkout -e prod --no-debug \
    && rm .env

RUN git clone git@bitbucket.org:agreementbb/service_discovery_scheme.git -b dev service_discovery_scheme

FROM source as test

COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-php-ini-overrides.ini
COPY --chown=www-data:www-data ./app/symfony.lock symfony.lock
RUN composer check-platform-reqs \
    && composer install \
        --prefer-dist \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts

#COPY --chown=www-data:www-data ./app/behat.yml.dist behat.yml.dist
#COPY --chown=www-data:www-data ./app/behat.yml.dist behat.yml
#COPY --chown=www-data:www-data ./app/features  ./features
COPY --chown=www-data:www-data ./app/.env .env
#COPY --chown=www-data:www-data ./app/.php_cs.dist .php_cs

FROM base as prod

USER www-data

COPY --from=source --chown=www-data:www-data /var/www/symfony/ .
RUN touch /var/www/symfony/.env
COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-php-ini-overrides.ini
