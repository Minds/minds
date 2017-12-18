FROM minds/php:latest

# Additional folders

RUN mkdir --parents --mode=0777 /tmp/minds-cache/ \
    && mkdir --parents --mode=0777 /data/

# Copy our built the code

ADD --chown=www-data . /var/www/Minds/engine

# Remove the local settings file (if it exists)

RUN rm -f /var/www/Minds/engine/settings.php

# Install awscli

RUN apk update && apk add --no-cache py-pip && pip install --upgrade pip && pip install awscli

# Copy secrets script

COPY containers/php-fpm/pull-secrets.sh pull-secrets.sh

# Copy config

COPY containers/php-fpm/php.ini /usr/local/etc/php/
COPY containers/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache-recommended.ini
COPY containers/php-fpm/apcu.ini /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
COPY containers/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf