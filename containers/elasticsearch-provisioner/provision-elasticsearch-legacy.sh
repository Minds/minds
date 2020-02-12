#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

echo "Provisioning legacy elastic search";
echo "Waiting for legacy elastic search to come online..."
./wait-for.sh $1:9200 --timeout=120 -- echo "Legacy elastic search is up and running"

echo "Putting mappings"
echo "Putting minds_badger"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds_badger -d @./schema-legacy/minds_badger.json --header "Content-Type: application/json"


echo "Putting minds-graph"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-graph -d @./schema-legacy/minds-graph.json --header "Content-Type: application/json"

echo "Putting minds-helpdesk"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-helpdesk -d @./schema-legacy/minds-helpdesk.json --header "Content-Type: application/json"

echo "Putting minds-kite"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-kite -d @./schema-legacy/minds-kite.json --header "Content-Type: application/json"

echo "Putting minds-metrics"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-06-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-07-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-08-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-09-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-10-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-11-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-metrics-12-2019 -d @./schema-legacy/minds-metrics.json --header "Content-Type: application/json"

echo "Putting minds-moderation"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-moderation -d @./schema-legacy/minds-moderation.json --header "Content-Type: application/json"

echo "Putting minds-trending-hashtags-shrunk"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/minds-trending-hashtags-shrunk -d @./schema-legacy/minds-trending-hashtags-shrunk.json --header "Content-Type: application/json"

