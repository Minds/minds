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

require_once '../../Google_Client.php';
require_once '../../contrib/Google_PredictionService.php';

// Set your client id, service account name, and the path to your private key.
// For more information about obtaining these keys, visit:
// https://developers.google.com/console/help/#service_accounts
const CLIENT_ID = 'INSERT_YOUR_CLIENT_ID';
const SERVICE_ACCOUNT_NAME = 'INSERT_YOUR_SERVICE_ACCOUNT_NAME';

// Make sure you keep your key.p12 file in a secure location, and isn't
// readable by others.
const KEY_FILE = '/super/secret/path/to/key.p12';

$client = new Google_Client();
$client->setApplicationName("Google Prediction Sample");

// Set your cached access token. Remember to replace $_SESSION with a
// real database or memcached.
session_start();
if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

// Load the key in PKCS 12 format (you need to download this from the
// Google API Console when the service account was created.
$key = file_get_contents(KEY_FILE);
$client->setAssertionCredentials(new Google_AssertionCredentials(
    SERVICE_ACCOUNT_NAME,
    array('https://www.googleapis.com/auth/prediction'),
    $key)
);

$client->setClientId(CLIENT_ID);
$service = new Google_PredictionService($client);


// Prediction logic:
$id = 'sample.languageid';
$predictionData = new Google_InputInput();
$predictionData->setCsvInstance(array('Je suis fatigue'));

$input = new Google_Input();
$input->setInput($predictionData);

$result = $service->hostedmodels->predict($id, $input);
print '<h2>Prediction Result:</h2><pre>' . print_r($result, true) . '</pre>';

// We're not done yet. Remember to update the cached access token.
// Remember to replace $_SESSION with a real database or memcached.
if ($client->getAccessToken()) {
  $_SESSION['token'] = $client->getAccessToken();
}