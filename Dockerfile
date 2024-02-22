FROM php:8.3-alpine AS application_builder
WORKDIR /app

COPY . ./

RUN mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/sessions \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'edb40769019ccf227279e3bdd1f5b2e9950eb000c3233ee85148944e555d97be3ea4f40c3c2fe73b22f875385f6a5155') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && composer install --optimize-autoloader --no-dev


FROM node:20.11-alpine As asset_builder
WORKDIR /app

COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./vite.config.js ./
COPY ./postcss.config.cjs ./
COPY ./tailwind.config.cjs ./
COPY ./resources ./resources

RUN npm install \
    && npm run build


FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache \
    && apk add --no-cache \
    mariadb-client \
    sqlite \
    nginx

COPY . ./

COPY --from=application_builder /app/vendor ./vendor
COPY --from=application_builder /app/bootstrap/cache ./bootstrap/cache

COPY --from=asset_builder /app/public/build ./public/build

RUN mkdir ./database/sqlite \
    && chown -R www-data: /var/www/html \
    && rm -rf ./docker

COPY ./docker/config/servas-php.ini /usr/local/etc/php/conf.d/servas-php.ini
COPY ./docker/config/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/config/site-nginx.conf /etc/nginx/http.d/default.conf

COPY ./docker-entrypoint.sh /

EXPOSE 80

ENTRYPOINT ["/docker-entrypoint.sh"]
