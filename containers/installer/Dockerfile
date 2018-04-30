FROM minds/php:latest

RUN apk update && apk add --no-cache git nodejs

COPY containers/installer/install.sh install.sh

ENTRYPOINT [ "sh", "./install.sh" ]