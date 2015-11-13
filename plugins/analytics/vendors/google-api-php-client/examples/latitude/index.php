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
session_start();

require_once '../../Google_Client.php';
require_once '../../contrib/Google_LatitudeService.php';

$client = new Google_Client();
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
$client->setApplicationName("Latitude_Example_App");
$service = new Google_LatitudeService($client);

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}

if ($client->getAccessToken()) {
  // Start to make API requests.
  //$location = $service->location->listLocation();
  $currentLocation = $service->currentLocation->get();
  $_SESSION['access_token'] = $client->getAccessToken();
}
?>
<!doctype html>
<html>
<head><link rel='stylesheet' href='style.css' /></head>
<body>
<header><h1>Google Latitude Sample App</h1></header>
<div class="box">
  <?php if(isset($currentLocation)): ?>
    <div class="currentLocation">
      <pre><?php var_dump($currentLocation); ?></pre>
    </div>
  <?php endif ?>

  <?php if (isset($location)): ?>
    <div class="location">
      <pre><?php var_dump($location); ?></pre>
    </div>
  <?php endif ?>

  <?php
    if(isset($authUrl)) {
      print "<a class='login' href='$authUrl'>Connect Me!</a>";
    } else {
     print "<a class='logout' href='?logout'>Logout</a>";
    }
  ?>
</div>
</body></html>