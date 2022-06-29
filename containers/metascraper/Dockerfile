FROM node:fermium-alpine

WORKDIR /var/www/Minds/

RUN apk add curl docker git --no-cache && \
    git clone https://gitlab.com/minds/developers/metascraper-server.git/ && \
    cd metascraper-server && \
    npm i

WORKDIR /var/www/Minds/metascraper-server

CMD npm run build && npm run start
