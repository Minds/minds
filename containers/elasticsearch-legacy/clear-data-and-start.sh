#!/bin/bash
rm -rf /usr/share/elasticsearch/data/*
exec /docker-entrypoint.sh elasticsearch