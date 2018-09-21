FROM php:7.1-fpm-alpine

# Required extensions
RUN apk update && apk add --no-cache \
    coreutils \
    libmcrypt-dev \
    zlib-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    $PHPIZE_DEPS \
    openssl-dev \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-install -j$(nproc) mbstring

# Cassandra extension

ENV INSTALL_DIR /usr/src/datastax-php-driver

ENV BUILD_DEPS \
    bash \
    cmake \
    autoconf \
    g++ \
    gcc \
    make \
    pcre-dev \
    libuv-dev \
    git \
    gmp-dev

RUN apk update && apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && apk add --no-cache libuv gmp \
    && git clone https://github.com/datastax/php-driver.git $INSTALL_DIR \
    && cd $INSTALL_DIR \
    && git submodule update --init \
    && cd ext && bash $INSTALL_DIR/ext/install.sh \
    && docker-php-ext-enable cassandra \
    && apk del .build-deps \
    && rm -rf $INSTALL_DIR

# Mongo extension

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Redis extension

RUN pecl install redis \
    && docker-php-ext-enable redis

# APCu extension

RUN pecl install apcu \
    && docker-php-ext-enable apcu

# ZMQ extension

RUN apk add --no-cache zeromq-dev && \
    pecl install zmq-beta \
    && docker-php-ext-enable zmq

# Install nodejs (needed for mw3)

RUN apk add --update --no-cache nodejs

# Install ffmpeg (needed for video transcodes)

RUN apk add --no-cache ffmpeg

# PHP INI

COPY php.ini /usr/local/etc/php/
COPY opcache.ini /usr/local/etc/php/conf.d/opcache-recommended.ini