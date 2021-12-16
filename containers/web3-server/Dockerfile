FROM mhart/alpine-node:12.22

WORKDIR /var/www/Minds/

RUN apk add curl docker git --no-cache && \
    git clone https://gitlab.com/minds/web3-server.git/ && \
    cd web3-server && \
    npm i

WORKDIR /var/www/Minds/web3-server

CMD npm run build && node dist/server.js
