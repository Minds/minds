FROM minds/php:latest

# Additional folders

RUN mkdir --parents --mode=0777 /tmp/minds-cache/ \
    && mkdir --parents --mode=0777 /data/

# Copy config

COPY containers/php-fpm/php.ini /usr/local/etc/php/
#COPY containers/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache-recommended.ini
COPY containers/php-fpm/apcu.ini /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
COPY containers/php-fpm/php-fpm.dev.conf /usr/local/etc/php-fpm.d/www.conf