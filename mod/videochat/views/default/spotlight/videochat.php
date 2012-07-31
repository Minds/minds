<?php

  // vwconnect
if (is_plugin_enabled (vwconnect)) {
	// twitter
	/* Load required lib files. */
	$vwconnect = 0;
	if (is_dir('../vwconnect')) {
		session_start();
		require_once('../vwconnect/twitteroauth/twitteroauth.php');
		require_once('../vwconnect/config.php');
		$vwconnect = 1;
	} elseif (is_dir('../../mod/vwconnect')) {
		session_start();
		require_once('../../mod/vwconnect/twitteroauth/twitteroauth.php');
		require_once('../../mod/vwconnect/config.php');
		$vwconnect = 2;
	}
	if ($vwconnect > 0) {
	if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			$socialconnect2 = "".
											 "<br /><a href=\"".$CONFIG->url."pg/vwconnect/connect?service=twitter\">".
											 //"<img alt=\"Sign in with Twitter\" src=\"mod/vwconnect/graphics/lighter.png\">".
											 "Connect to Twitter</a></div>";
	} else {
		/* Get user access tokens out of the session. */
		$access_token = $_SESSION['access_token'];

		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

		/* If method is set change API call made. Test is called by default. */
		$twitter = $connection->get('account/verify_credentials');
		// $connection->post('statuses/update', array('status' => date(DATE_RFC822)));
		$socialconnect2 = "<br />Connected on Twitter as:<a href=\"http://twitter.com/".$twitter->screen_name."\">".$twitter->screen_name."</a>, <a href=\"".$CONFIG->url."mod/vwconnect/clearsessions.php\">Disconnect</a>";
	}
	}
	// akhir twitter
	
	// prosedur fb
	if ($vwconnect == 1)
		require '../vwconnect/src/facebook.php';
	else 
		require '../../mod/vwconnect/src/facebook.php';
		
// Create our Application instance (replace this with your appId and secret).
$appId = datalist_get('vwconnect_facebook_appId');
$secret = datalist_get('vwconnect_facebook_secret');

$facebook = new Facebook(array(
  'appId' => $appId,
  'secret' => $secret,
  'cookie' => true,
));

	// Get User ID
	$fbuser = $facebook->getUser();

	// We may or may not have this data based on whether the user is logged in.
	//
	// If we have a $user id here, it means we know the user is logged into
	// Facebook, but we don't know if the access token is valid. An access
	// token is invalid if the user logged out of Facebook.
	$socialconnect1 = "<br /><a href=\"".$CONFIG->url."pg/vwconnect/connect?service=facebook\">Connect to facebook</a>";

	if ($fbuser) {
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me');
			$logoutUrl = $facebook->getLogoutUrl();
			$socialconnect1 = "<br />Connected on Facebook as:<a href=\"".$user_profile['link']."\">".$user_profile['email']."</a>, <a href=\"".$logoutUrl."\">Disconnect</a>";
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
			$socialconnect1 = "<br /><a href=\"".$CONFIG->url."pg/vwconnect/connect?service=facebook\">Connect to facebook</a>";
		}
	}
	// akhir fb
}	

// like button facebook and twitter
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

?>
<div id="spotlight_table" >
	<div id="spotlight_left_column">
For more details about this application visit the homepage of the <a href="http://www.videowhisper.com/">VideoWhisper</a>, <a href="http://www.videowhisper.com/?p=2+Way+Video+Chat">1 On 1 Video Chat Application</a> and <a href="http://www.videowhisper.com/?p=Elgg+Video+Chat">Elgg Video Chat Plugin</a>
	<?php 
		echo $socialconnect1.''.$socialconnect2;
		$currenturl = urlencode(curPageURL());
		// if in urlname ".php", so it's not a video room
		$getphp	= substr_count ($currenturl, ".php");
		if ($getphp == 0) {
		?>
<br />
<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<iframe src="http://www.facebook.com/plugins/like.php?app_id=<?=$appId?>&amp;href=<?echo $currenturl;?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
		<? }
		?>
	</div>
</div>
<div class="clearfloat"></div>
