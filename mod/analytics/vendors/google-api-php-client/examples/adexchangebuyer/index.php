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

/**
* Implements the examples execution flow.
* Load this file with no parameters to get the list of available examples.
*
* @author David Torres <david.t@google.com>
*/

require_once "../../src/Google_Client.php";
require_once "../../src/contrib/Google_AdexchangebuyerService.php";
require_once "htmlHelper.php";

session_start();

$client = new Google_Client();
$client->setApplicationName('DoubleClick Ad Exchange Buyer API PHP Samples');
// Visit https://code.google.com/apis/console?api=adexchangebuyer to generate
// your client id, client secret, and to register your redirect uri.
$client->setScopes(array('https://www.googleapis.com/auth/adexchange.buyer'));
// Visit https://code.google.com/apis/console?api=adexchangebuyer to generate
// your oauth2_client_id, oauth2_client_secret, and to register your
// oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_simple_api_key');
$service = new Google_AdexchangebuyerService($client);

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  // Build the list of supported actions.
  $actions = getSupportedActions();

  // If the action is set dispatch the action if supported
  if (isset($_GET["action"])) {
    $action = $_GET["action"];
    if (!in_array($action, $actions)) {
      die('Unsupported action:' . $action . "\n");
    }
    // Render the required action.
    require_once 'examples/' . $action . '.php';
    $class = ucfirst($action);
    $example = new $class($service);
    printHtmlHeader($example->getName());
    try {
      $example->execute();
    } catch (Google_Exception $ex) {
      printf('An error as occurred while calling the example:<br/>');
      printf($ex->getMessage());
    }
    printSampleHtmlFooter();
  } else {
    // Show the list of links to supported actions.
    printHtmlHeader('Ad Exchange Buyer API PHP usage examples.');
    printExamplesIndex($actions);
    printHtmlFooter();
  }

  // The access token may have been updated.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}

/**
 * Builds an array containing the supported actions.
 */
function getSupportedActions() {
  return array('GetAllAccounts', 'GetCreative', 'GetDirectDeals',
               'SubmitCreative', 'UpdateAccount');
}
