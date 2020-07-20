FROM mhart/alpine-node:12

WORKDIR /var/www/Minds/

RUN apk add curl docker git --no-cache && \
    git clone https://gitlab.com/benhayward.ben/minds-permaweb-server.git/ && \
    cd minds-permaweb-server && \
    npm i

WORKDIR /var/www/Minds/minds-permaweb-server

CMD node server.js
