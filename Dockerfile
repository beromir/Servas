FROM composer AS application_builder
WORKDIR /app

COPY . ./

RUN mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/sessions \
    && composer install


FROM node:17.4-alpine As asset_builder
WORKDIR /app

COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./webpack.mix.js ./
COPY ./webpack.config.js ./
COPY ./tailwind.config.js ./
COPY ./resources ./resources

RUN npm install \
    && npm run prod


FROM php:8.1-apache
WORKDIR /var/www/html

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && apt-get update \
    && apt-get install -y mariadb-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --chown=www-data:www-data . ./

COPY --chown=www-data:www-data --from=application_builder /app/vendor ./vendor
COPY --chown=www-data:www-data --from=application_builder /app/bootstrap/cache ./bootstrap/cache

COPY --chown=www-data:www-data --from=asset_builder /app/public/assets ./public/assets
COPY --chown=www-data:www-data --from=asset_builder /app/public/css ./public/css
COPY --chown=www-data:www-data --from=asset_builder /app/public/js ./public/js
COPY --chown=www-data:www-data --from=asset_builder /app/public/mix-manifest.json ./public/mix-manifest.json

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

EXPOSE 80
