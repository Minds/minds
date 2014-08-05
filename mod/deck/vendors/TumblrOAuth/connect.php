<?php
// This script is a simple example of how to send a user off to authentication using Tumblr's OAuth

// Start a session.  This is necessary to hold on to  a few keys the callback script will also need
session_start();

// Include the TumblrOAuth library
require_once('tumblroauth/tumblroauth.php');

// Define the needed keys
$consumer_key = "YOUR CONSUMER KEY GOES HERE";
$consumer_secret = "PUT THE SECRET KEY HERE";

// The callback URL is the script that gets called after the user authenticates with tumblr
// In this example, it would be the included callback.php
$callback_url = "PUT A CALLBACK URL HERE";

// Let's begin.  First we need a Request Token.  The request token is required to send the user
// to Tumblr's login page.

// Create a new instance of the TumblrOAuth library.  For this step, all we need to give the library is our
// Consumer Key and Consumer Secret
$tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret);

// Ask Tumblr for a Request Token.  Specify the Callback URL here too (although this should be optional)
$request_token = $tum_oauth->getRequestToken($callback_url);

// Store the request token and Request Token Secret as out callback.php script will need this
$_SESSION['request_token'] = $token = $request_token['oauth_token'];
$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

// Check the HTTP Code.  It should be a 200 (OK), if it's anything else then something didn't work.
switch ($tum_oauth->http_code) {
  case 200:
    // Ask Tumblr to give us a special address to their login page
    $url = $tum_oauth->getAuthorizeURL($token);
	
	// Redirect the user to the login URL given to us by Tumblr
    header('Location: ' . $url);
	
	// That's it for our side.  The user is sent to a Tumblr Login page and
	// asked to authroize our app.  After that, Tumblr sends the user back to
	// our Callback URL (callback.php) along with some information we need to get
	// an access token.
	
    break;
default:
    // Give an error message
    echo 'Could not connect to Tumblr. Refresh the page or try again later.';
}
exit();

?>