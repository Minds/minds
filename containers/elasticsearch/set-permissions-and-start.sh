#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

chown -R elasticsearch:elasticsearch /usr/share/elasticsearch/data
exec /usr/local/bin/docker-entrypoint.sh elasticsearch
