FROM mhart/alpine-node:12

WORKDIR /var/www/Minds/

RUN apk add curl docker git --no-cache && \
    git clone https://gitlab.com/benhayward.ben/minds-permaweb-server.git/ && \
    cd minds-permaweb-server && \
    npm i

WORKDIR /var/www/Minds/minds-permaweb-server

CMD node server.js

# WORKDIR /
# COPY wait-for.sh provision-elasticsearch.sh provision-elasticsearch-legacy.sh /provisioner/
# COPY schema-legacy/ /provisioner/schema-legacy/
# COPY schema/ /provisioner/schema/
# ENTRYPOINT ["sh", "./provision-elasticsearch.sh", "elasticsearch"]
