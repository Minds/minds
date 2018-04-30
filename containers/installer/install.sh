#!/bin/sh

echo "INSTALLING MINDS"

cd /var/www/Minds/engine
sh /var/www/Minds/engine/tools/setup.sh

echo "Setting up Keys"

php /var/www/Minds/engine/cli.php install keys

echo "Running install"

php /var/www/Minds/engine/cli.php install \
    --domain=localhost:8080 \
    --username=minds \
    --password=password \
    --email=minds@minds.com \
    --email-private-key=/.dev/minds.pem \
    --email-public-key=/.dev/minds.pub \
    --phone-number-private-key=/.dev/minds.pem \
    --phone-number-public-key=/.dev/minds.pub \
    --cassandra-server=cassandra
