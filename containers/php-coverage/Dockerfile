FROM minds/php:latest

# Additional folders

RUN mkdir --parents --mode=0777 /tmp/minds-cache/ \
    && mkdir --parents --mode=0777 /data/

# Copy our built the code

ADD --chown=www-data . /var/www/Minds/engine

# Install awscli

RUN apk update && pecl install xdebug && docker-php-ext-enable xdebug && apk add --no-cache py-pip && pip install --upgrade pip && pip install awscli

# Copy config

COPY containers/php-fpm-coverage/php.ini /usr/local/etc/php/
COPY containers/php-fpm-coverage/opcache.ini /usr/local/etc/php/conf.d/opcache-recommended.ini
COPY containers/php-fpm-coverage/apcu.ini /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
COPY containers/php-fpm-coverage/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf