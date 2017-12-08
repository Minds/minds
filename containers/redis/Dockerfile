FROM redis:4.0-alpine

COPY master.conf /usr/local/etc/redis/master.conf
#COPY slave.conf /usr/local/etc/redis/slave.conf

CMD [ "redis-server", "/usr/local/etc/redis/master.conf" ]