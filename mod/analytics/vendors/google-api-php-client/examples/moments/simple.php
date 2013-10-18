<?php
require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_PlusMomentsService.php';

session_start();

// Visit the Google+ history documentation to enable the Google+ history:
// https://developers.google.com/+/history/#getting_started
$client = new Google_Client();
$client->setApplicationName("Google+ history example");
$client->setClientId('insert_client_id');
$client->setClientSecret('insert_client_secret');
$client->setRedirectUri('insert_redirect_uri');
$client->setDeveloperKey('insert_developer_key');

$moments = new Google_PlusMomentsService($client);

if (isset($_GET['signout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
  // Validate the state parameter (the CSRF token generated with the
  // Google+ sign-in button).
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die("The session state did not match.");
  }

  unset($_SESSION['state']);

  // Receive an OAuth 2.0 authorization code via the GET parameter 'code'.
  // Exchange the OAuth 2.0 authorization code for user credentials.
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  print '<script type="text/javascript">window.close();</script>';
  exit(0);
}

// Recall the credentials from the session.  In practice, you want to
// look-up the token from a database.
if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->isAccessTokenExpired()) {

  // Generate a unique CSRF token.
  $state = sha1(uniqid(mt_rand(), true));
  $_SESSION['state'] = $state;

  // Render the Google+ sign-in button.
  print <<<MARKUP
<!doctype html><html><head>
<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
<script type="text/javascript">
  // The simplest possible solution to this callback. In your application,
  // you would want to replace the button with markup that indicates the state.
  function onSignIn() {
    window.location.reload(true);
  }
</script></head>
<body>
<g:plus action="connect"
  clientid="{$client->getClientId()}"
  redirecturi="{$client->getRedirectUri()}"
  scope="https://www.googleapis.com/auth/plus.moments.write"
  state="$state" callback="onSignIn">
</g:plus></body></html>
MARKUP;

} else {
  // Build the moment to write
  $target = new Google_ItemScope();
  $target->url = 'https://developers.google.com/+/plugins/snippet/examples/thing';

  $moment = new Google_Moment();
  $moment->type = "http://schemas.google.com/AddActivity";
  $moment->target = $target;

  // Execute the request
  $moments->moments->insert('me', 'vault', $moment);
  print '<p>Created an AddActivity moment</p>';

  $_SESSION['token'] = $client->getAccessToken();
}