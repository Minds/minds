FROM mhart/alpine-node:12

WORKDIR /var/www/Minds/

RUN apk add curl docker git --no-cache && \
    git clone https://gitlab.com/minds/minds-permaweb-node.git/ && \
    cd minds-permaweb-node && \
    npm i

WORKDIR /var/www/Minds/minds-permaweb-node

CMD node server.js
