<?php
require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_SiteVerificationService.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google Site Verification PHP Sample");

// Visit https://code.google.com/apis/console?api=siteVerification to generate
// your client id, client secret, and to register your redirect uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_developer_key');
$service = new Google_SiteVerificationService($client);

if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

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
  $resources = $service->webResource->listWebResource();
  print '<pre>' . print_r($resources, true) . '</pre>';

  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}