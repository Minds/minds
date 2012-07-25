<?php
/**
 * Settings
 */
 
elgg_load_library('minds:facebook');

$facebook = minds_social_facebook_init();

$user = $facebook->getUser();

$user_id = elgg_get_logged_in_user_guid();
$facebook_id = elgg_get_plugin_user_setting('minds_social_facebook_uid', $user_id, 'minds_social');
$access_token = elgg_get_plugin_user_setting('minds_social_facebook_access_token', $user_id, 'minds_social');


$return_url = elgg_get_site_url() . 'social/fb/auth';

$login_url = $facebook->getLoginUrl(array(
				'redirect_uri' => $return_url,
				'canvas' => 1,
				'scope' => 'publish_stream, offline_access',
				'ext_perm' =>  'offline_access',
			));


echo "<h3> Facebook </h3>";
if($facebook_id == $user){
	
	echo '<div><a href="' . $login_url . '">Re-connect</a></div>';


} else {
	echo '<div><a href="' . $login_url . '">Login</a></div>';

}

elgg_load_library('minds:twitter');

echo "<h3> Twitter </h3>";

$url = elgg_get_site_url() . 'social/twitter/forward';
$img_url = elgg_get_site_url() . 'mod/twitter_api/graphics/sign-in-with-twitter-d.png';

$login = <<<__HTML
<div id="login_with_twitter">
	<a href="$url">
		<img src="$img_url" alt="Twitter" />
	</a>
</div>
__HTML;

echo $login;


echo "<br/>";


