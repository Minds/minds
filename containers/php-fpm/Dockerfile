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

# Copy config

COPY containers/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf