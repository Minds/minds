<?php
require_once '../../Google_Client.php';
require_once '../../contrib/Google_ShoppingService.php';

$client = new Google_Client();
$client->setApplicationName("Google Shopping PHP Starter Application");

// Visit https://code.google.com/apis/console?api=shopping to generate your
// Simple API Key.
//$client->setDeveloperKey('insert_your_api_key');
$service = new Google_ShoppingService($client);

// Valid source values are "public", "cx:cse", and "gan:pid"
// See http://code.google.com/apis/shopping/search/v1/getting_started.html#products-feed
$source = "public";

// For more information about full text search with the shopping API, please
// see http://code.google.com/apis/shopping/search/v1/getting_started.html#text-search
$query = "\"mp3 player\" | ipod";

//The order in which the API returns products is defined by a ranking criterion.
// See http://code.google.com/apis/shopping/search/v1/getting_started.html#ranking
$ranking = "relevancy";

$results = $service->products->listProducts($source, array(
  "country" => "US",
  "q" => $query,
  "rankBy" => $ranking,
));

print "<h1>Shopping Results</h1><pre>" . print_r($results, true) . "</pre>";
