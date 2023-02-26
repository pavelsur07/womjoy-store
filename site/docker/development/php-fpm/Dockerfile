FROM php:8.2-fpm-alpine

ENV XDEBUG_VERSION 3.2.0

RUN apk add --no-cache autoconf g++ make rabbitmq-c-dev \
    && pecl install -o -f redis amqp \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis amqp

RUN apk add --no-cache postgresql-dev fcgi git \
    libjpeg-turbo-dev libpng-dev libwebp-dev freetype-dev  \
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

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./common/php/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY ./development/php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1


#COPY ./development/php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
#RUN chmod +x /usr/local/bin/docker-php-entrypoint

#HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
#    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
#    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
