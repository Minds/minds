FROM minds/php:latest

RUN apk update && apk add --no-cache --update git

COPY containers/installer/install.sh install.sh

ENTRYPOINT [ "sh", "./install.sh" ]
