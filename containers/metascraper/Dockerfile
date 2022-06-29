FROM node:fermium-alpine

WORKDIR /var/www/Minds/

RUN apk add curl docker git --no-cache && \
    git clone https://gitlab.com/benhayward.ben/minds-metascraper-server.git/ && \
    cd minds-metascraper-server && \
    npm i

WORKDIR /var/www/Minds/minds-metascraper-server

CMD npm run build && npm run start
