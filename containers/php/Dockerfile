FROM php:7.0-fpm-alpine

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
    && docker-php-ext-install -j$(nproc) mcrypt \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

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

RUN pecl install mongodb-1.2.9 \
    && docker-php-ext-enable mongodb

# Redis extension

RUN pecl install redis-3.1.3 \
    && docker-php-ext-enable redis
