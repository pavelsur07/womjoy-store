FROM php:8.2-cli-alpine

ENV XDEBUG_VERSION 3.2.0

RUN apk add --no-cache autoconf g++ make rabbitmq-c-dev \
    && pecl install -o -f redis amqp \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis amqp

RUN apk add --no-cache postgresql-dev \
    libjpeg-turbo-dev libpng-dev libwebp-dev freetype-dev \
    bash coreutils git \
    jpegoptim optipng pngquant gifsicle \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    #&& git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    #&& docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-configure gd \
            --with-jpeg \
            --with-webp \
            --with-freetype \
    #&& docker-php-ext-install pdo_pgsql gd xdebug bcmath \
    && docker-php-ext-install pdo_pgsql gd bcmath \
    && apk del git

RUN apk add --no-cache unzip

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

#ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app