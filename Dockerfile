FROM php:8.3-alpine AS application_builder
WORKDIR /app

COPY . ./

RUN mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/sessions \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && composer install --optimize-autoloader --no-dev


FROM node:20.11-alpine AS asset_builder
WORKDIR /app

COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./vite.config.js ./
COPY ./postcss.config.cjs ./
COPY ./tailwind.config.cjs ./
COPY ./resources ./resources

RUN npm install \
    && npm run build


FROM dunglas/frankenphp:1.3-php8.3-alpine
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

COPY ./docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]
