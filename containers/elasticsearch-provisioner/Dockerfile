FROM alpine:latest

RUN apk add curl docker --no-cache
WORKDIR /provisioner
COPY wait-for.sh provision-elasticsearch.sh provision-elasticsearch-legacy.sh /provisioner/
COPY schema-legacy/ /provisioner/schema-legacy/
COPY schema/ /provisioner/schema/
ENTRYPOINT ["sh", "./provision-elasticsearch.sh", "elasticsearch"]
