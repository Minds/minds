#!/bin/sh

echo "INSTALLING MINDS"

echo "Setting up Keys"

php /var/www/Minds/engine/cli.php install keys

echo "Running install"

php /var/www/Minds/engine/cli.php install \
    --domain=localhost:8080 \
    --username=minds \
    --password=password \
    --email=minds@minds.com \
    --private-key=/.dev/minds.pem \
    --public-key=/.dev/minds.pub \
    --cassandra-server=cassandra
