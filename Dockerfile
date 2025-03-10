FROM dunglas/frankenphp:php8.3-alpine AS application_builder
WORKDIR /app

COPY . ./

RUN install-php-extensions \
    @composer

RUN composer install --optimize-autoloader --no-dev && \
    composer dump-autoload --no-dev --classmap-authoritative


FROM node:20.11-alpine AS asset_builder
WORKDIR /app

COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./vite.config.js ./
COPY ./postcss.config.cjs ./
COPY ./tailwind.config.cjs ./
COPY ./resources ./resources

RUN npm install && \
    npm run build


FROM dunglas/frankenphp:php8.3-alpine
WORKDIR /app

ENV SERVER_NAME=:80

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions \
	pdo_mysql \
	mysqli \
	opcache \
	bcmath \
    && apk add --no-cache \
    mariadb-client \
    sqlite

COPY . ./

COPY --from=application_builder /app/vendor ./vendor
COPY --from=application_builder /app/bootstrap/cache ./bootstrap/cache

COPY --from=asset_builder /app/public/build ./public/build

RUN rm -rf ./docker

COPY ./Caddyfile /etc/caddy/Caddyfile
COPY docker/config/custom-php.ini /usr/local/etc/php/conf.d/zzz-custom-php.ini
COPY docker/config/custom-php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom-php-fpm.conf

RUN mkdir -p /var/cache/php/opcache && \
    chmod 700 /var/cache/php/opcache

COPY ./docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]
