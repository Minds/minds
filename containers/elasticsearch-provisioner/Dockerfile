FROM alpine:latest

RUN apk add curl docker --no-cache
WORKDIR /provisioner
COPY wait-for.sh provision-elasticsearch.sh /provisioner/
COPY schema/ /provisioner/schema/
ENTRYPOINT ["sh", "./provision-elasticsearch.sh", "elasticsearch"]
