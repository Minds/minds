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
require_once '../../src/Google_Client.php';

session_start();

$client = new Google_Client();
$client->setApplicationName("Google Apps PHP Starter Application");
$client->setScopes(array(
    'https://apps-apis.google.com/a/feeds/groups/',
    'https://apps-apis.google.com/a/feeds/alias/',
    'https://apps-apis.google.com/a/feeds/user/',
));

// Documentation: http://code.google.com/googleapps/domain/provisioning_API_v2_developers_guide.html
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_simple_api_key');

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
  // Retrieving a Single User in a Domain:
  $domain = "example.com";
  $user = rawurlencode("user@domain.com");
  $req = new Google_HttpRequest("https://apps-apis.google.com/a/feeds/$domain/$user/2.0");
  $resp = $client::getIo()->authenticatedRequest($req);
  print "<h1>Single User</h1>: <pre>" . $resp->getResponseBody() . "</pre>";

  //Retrieving All User Aliases for a User
  $domain = "example.com";
  $user = rawurlencode("user@domain.com");
  $req = new Google_HttpRequest("https://apps-apis.google.com/a/feeds/alias/2.0/$domain?userEmail=$user");
  $resp = $client::getIo()->authenticatedRequest($req);
  print "<h1>All User Aliases for User</h1>: <pre>" . $resp->getResponseBody() . "</pre>";

  // Deleting a User Alias from a Domain (Experimental)
  $domain = "example.com";
  $user = rawurlencode("user@domain.com");
  $req = new Google_HttpRequest("https://apps-apis.google.com/a/feeds/alias/2.0/$domain/$user", 'DELETE');
  $resp = $client::getIo()->authenticatedRequest($req);
  print "<h1>Deleting a User Alias from a Domain</h1>: <pre>" . $resp->getResponseBody() . "</pre>";


  // Retrieving List of 100 Nicknames
  $req = new Google_HttpRequest("https://apps-apis.google.com/a/feeds/domain/nickname/2.0");
  $resp = $client::getIo()->authenticatedRequest($req);
  print "<h1>Retrieving List of 100 Nicknames</h1>: <pre>" . $resp->getResponseBody() . "</pre>";

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
