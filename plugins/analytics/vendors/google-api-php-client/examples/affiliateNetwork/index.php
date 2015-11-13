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
require_once '../../contrib/Google_GanService.php';

session_start();

$client = new Google_Client();
$client->setApplicationName("Google GAN PHP Starter Application");
// Visit https://code.google.com/apis/console?api=gan to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_simple_api_key');
$gan = new Google_GanService($client);

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
  $publishers = $gan->publishers->listPublishers("advertisers", "INSERT_ROLE_ID" /* The ID of the requesting advertiser or publisher */);
  $advertisers = $gan->advertisers->listAdvertisers("publishers", "INSERT_ROLE_ID" /* The ID of the requesting advertiser or publisher */);
  print "<pre>" . print_r($publishers, true) . "</pre>";
  print "<pre>" . print_r($advertisers, true) . "</pre>";

  // The access token may have been updated lazily.
  $_SESSION['access_token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}

if(isset($authUrl)) {
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
} else {
 print "<a class='logout' href='?logout'>Logout</a>";
}