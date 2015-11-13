<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once '../../Google_Client.php';
require_once '../../contrib/Google_CustomsearchService.php';
session_start();

$client = new Google_Client();
$client->setApplicationName('Google CustomSearch PHP Starter Application');
// Docs: http://code.google.com/apis/customsearch/v1/using_rest.html
// Visit https://code.google.com/apis/console?api=customsearch to generate
// your developer key (simple api key).
// $client->setDeveloperKey('INSERT_your_developer_key');
$search = new Google_CustomsearchService($client);


// Example executing a search with your custom search id.
$result = $search->cse->listCse('burrito', array(
  'cx' => 'INSERT_SEARCH_ENGINE_ID', // The custom search engine ID to scope this search query.
));
print "<pre>" . print_r($result, true) . "</pre>";

// Example executing a search with the URL of a linked custom search engine.
$result = $search->cse->listCse('burrito', array(
  'cref' => 'http://www.google.com/cse/samples/vegetarian.xml',
));
print "<pre>" . print_r($result, true) . "</pre>";