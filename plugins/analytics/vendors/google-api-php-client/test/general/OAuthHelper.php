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

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

$client = new Google_Client();
// Visit https://code.google.com/apis/console to
// generate your oauth2_client_id, oauth2_client_secret, and to
// register your oauth2_redirect_uri.
//$client->setClientId('INSERT_CLIENT_ID');
//$client->setClientSecret('INSERT_CLIENT_SECRET');
$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
$client->setScopes(array(
  'https://www.googleapis.com/auth/plus.me',
  'https://www.googleapis.com/auth/latitude',
  'https://www.googleapis.com/auth/moderator',
  'https://www.googleapis.com/auth/tasks',
  'https://www.googleapis.com/auth/siteverification',
  'https://www.googleapis.com/auth/urlshortener',
  'https://www.googleapis.com/auth/adsense.readonly',
));

$authUrl = $client->createAuthUrl();

print "Please visit:\n$authUrl\n\n";
print "Please enter the auth code:\n";
$authCode = trim(fgets(STDIN));

$_GET['code'] = $authCode;
$accessToken = $client->authenticate();

var_dump($accessToken);
