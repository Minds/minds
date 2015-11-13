<?php
require_once '../../Google_Client.php';
require_once '../../contrib/Google_WebfontsService.php';

$client = new Google_Client();
$client->setApplicationName("Google WebFonts PHP Starter Application");

// Visit https://code.google.com/apis/console?api=webfonts
// to generate your developer key.
// $client->setDeveloperKey('insert_your_developer_key');
$service = new Google_WebfontsService($client);
$fonts = $service->webfonts->listWebfonts();
print "<h1>Fonts</h1><pre>" . print_r($fonts, true) . "</pre>";
