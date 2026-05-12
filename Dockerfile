FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install intl mbstring opcache pcntl pdo_mysql pdo_pgsql zip \
    && pecl install redis \
    && docker-php-ext-enable redis opcache \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN addgroup -g 1000 librepress \
    && adduser -D -G librepress -u 1000 librepress

USER librepress

CMD ["php-fpm"]

