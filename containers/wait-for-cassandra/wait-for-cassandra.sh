#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

echo "Waiting for Cassandra to come online..."
until cqlsh cassandra -e "show version"
do
    echo "Ping..."
    sleep 1
done

echo "Cassandra is up and running"
