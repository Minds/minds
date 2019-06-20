#!/bin/bash
chown -R elasticsearch:elasticsearch /usr/share/elasticsearch/data
exec /usr/local/bin/docker-entrypoint.sh elasticsearch