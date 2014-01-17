<?php

/**
 * Persona button (different plugin)
 */
$url = elgg_get_site_url() . 'social/twitter/forward';
$img_url = elgg_get_site_url() .'mod/minds_social/graphics/personax32.png';

$persona = <<<__HTML
<div class="social_login persona">
    <a href="#" id="persona_login" onclick="navigator.id.request(); return false;">
                <img src="$img_url" alt="Persona" />
		<p>Login with Persona</p>
    </a>
</div>
__HTML;



/*
 * Facebook Button
 */
elgg_load_library('minds:facebook');

/*$facebook = minds_social_facebook_init();

$return_url = elgg_get_site_url() . 'social/fb/login';

$login_url = $facebook->getLoginUrl(array(
				'redirect_uri' => $return_url,
				'canvas' => 1,
				'scope' => 'publish_stream,email, offline_access',
				'ext_perm' =>  'offline_access',
				'display' => 'popup',
			));
*/
$login_url = elgg_get_site_url() . 'social/fb/login';			
$facebook =  '<div class="social_login facebook"><a href="' . $login_url . '" target="_self"><img src="' . elgg_get_site_url() .'mod/minds_social/graphics/fbx32.png"/><p>Login with Facebook</p></a></div>';

/**
 * Twitter Button
 */
$url = elgg_get_site_url() . 'social/twitter/forward';
$img_url = elgg_get_site_url() .'mod/minds_social/graphics/twitterx32.png';

$twitter = <<<__HTML
<div class="social_login twitter">
	<a href="$url" target="_self">
		<img src="$img_url" alt="Twitter" />
		<p>Login with Twitter</p>
	</a>
</div>
__HTML;

?>

<div class="social-login">
	<?php echo $persona; echo $twitter; echo $facebook; ?>
</div>
