#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

echo "Provisioning elastic search";
echo "Waiting for elastic search to come online..."
./wait-for.sh $1:9200 --timeout=120 -- echo "Elastic search is up and running"

echo "Putting mappings"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-views -d @./schema/minds-views.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-boost -d @./schema/minds-boost.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-offchain -d @./schema/minds-offchain.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-transactions-onchain -d @./schema/minds-transactions-onchain.json --header "Content-Type: application/json"

echo "elastic search is ready!"
