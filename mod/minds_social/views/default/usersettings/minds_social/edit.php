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
$remove_url = elgg_get_site_url() . 'social/fb/remove';
$login_url = $facebook->getLoginUrl(array(
				'redirect_uri' => $return_url,
				'canvas' => 1,
				'scope' => 'publish_stream, offline_access',
				'ext_perm' =>  'offline_access',
			));

echo "<h3> Facebook </h3>";
if($facebook_id && $facebook_id != ''){
	
	echo '<div><a href="' . $remove_url . '">Remove facebook link</a></div>';


} else {
	echo '<div><a href="' . $login_url . '"><img src="' . elgg_get_site_url() .'mod/minds_social/graphics/facebook_connect.gif"/></a></div>';

}

elgg_load_library('minds:twitter');

echo "<h3> Twitter </h3>";

$url = elgg_get_site_url() . 'social/twitter/forward/auth';
$img_url = elgg_get_site_url() . 'mod/twitter_api/graphics/sign-in-with-twitter-d.png';

$login = <<<__HTML
<div id="login_with_twitter">
	<a href="$url">
		<img src="$img_url" alt="Twitter" />
	</a>
</div>
__HTML;

$remove_url = elgg_get_site_url() . 'social/twitter/remove';

$remove = <<<__HTML
<div id="login_with_twitter">
	<a href="$remove_url">
		Remove twitter link
	</a>
</div>
__HTML;

$connected = elgg_get_plugin_user_setting('minds_social_twitter_access_key',$user_id, 'minds_social');

if(!$connected)
	echo $login;
else 
	echo $remove;


echo "<br/>";


