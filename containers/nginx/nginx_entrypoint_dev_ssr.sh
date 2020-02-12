#!/bin/sh
set -e

export DOCKER_RESOLVER=$(cat /etc/resolv.conf | grep -i '^nameserver' | head -n1 | cut -d ' ' -f2)

envsubst \$UPSTREAM_ENDPOINT,\$DOCKER_RESOLVER < /dev-ssr.conf.tpl > /etc/nginx/conf.d/dev.conf

cat /etc/nginx/conf.d/dev.conf

nginx -g "daemon off;"
