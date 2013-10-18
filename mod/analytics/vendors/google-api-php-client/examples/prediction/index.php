<?php

/*
 * Copyright 2012 Google Inc.
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

require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_PredictionService.php';

session_start();

$client = new Google_Client();
$client->setApplicationName("Google Prediction API PHP Starter Application");
// Visit https://code.google.com/apis/console/?api=prediction to generate 
// your oauth2_client_id, oauth2_client_secret, and to register your 
// oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_developer_key');
$client->setScopes(array('https://www.googleapis.com/auth/prediction'));

$predictionService = new Google_PredictionService($client);
$trainedmodels = $predictionService->trainedmodels;

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
  $status = "Logged In";
} else {
  $status = "Logged Out";
  $authUrl = $client->createAuthUrl();
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link rel='stylesheet' href='style.css' />
</head>
<body>
<header><h1>Google Prediction API Sample App (PHP)</h1></header>
<div class="box">

<!--<div>Status: <?php print $status?></div>-->

<?php
  if(isset($authUrl)) {
    print "<a class='login' href='$authUrl'>Login</a>";
    $result = "";
    print("</div>");
  } else {
    print "<a class='login' href='?logout'>Logout</a>";
    /* prediction logic follows...  */
    $id = "languages";
    $predictionText = "Je suis fatigue";
    $predictionData = new Google_InputInput();
    $predictionData->setCsvInstance(array($predictionText));
    $input = new Google_Input();
    $input->setInput($predictionData);
    $result = $predictionService->trainedmodels->predict($id, $input);
    print("</div><br><br><h2>Prediction Result:</h2>");
    print_r($result);
  }
?>
</body>
</html>
