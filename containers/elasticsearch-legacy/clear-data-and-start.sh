#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

rm -rf /usr/share/elasticsearch/data/*
exec /docker-entrypoint.sh elasticsearch
