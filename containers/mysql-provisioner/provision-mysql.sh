#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

echo "Provisioning MySQL";
echo "Waiting for MySQL to come online..."

until mysql -h mysql -u root minds -e "SELECT 1"
do
    echo "Ping..."
    sleep 1
done

echo "MySQL is up and running"

echo "Creating tables"
mysql -h mysql -u root minds < provision.sql

echo "MySQL is ready!"
