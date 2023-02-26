FROM php:8.2-cli-alpine AS builder

RUN apk add --no-cache autoconf g++ make rabbitmq-c-dev \
    && pecl install -o -f redis amqp \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis amqp

RUN apk add --no-cache postgresql-dev \
    libjpeg-turbo-dev libpng-dev libwebp-dev freetype-dev \
    bash coreutils git \
    jpegoptim optipng pngquant gifsicle \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure gd \
            --with-jpeg \
            --with-webp \
            --with-freetype \
    && docker-php-ext-install pdo_pgsql gd opcache bcmath \
    && apk del git

RUN apk add --no-cache unzip

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --prefer-dist --no-progress --no-scripts --optimize-autoloader \
    && rm -rf /root/.composer/cache

### CLI ###

FROM php:8.2-cli-alpine

RUN apk add --no-cache autoconf g++ make

RUN apk add --no-cache postgresql-dev bash coreutils \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it

RUN chmod 555 /usr/local/bin/wait-for-it

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./

ENV APP_ENV prod

USER app