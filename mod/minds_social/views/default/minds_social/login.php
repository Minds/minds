<?php
/*
 * Facebook Button
 */
elgg_load_library('minds:facebook');

$facebook = minds_social_facebook_init();

$return_url = elgg_get_site_url() . 'social/fb/login';

$login_url = $facebook->getLoginUrl(array(
				'redirect_uri' => $return_url,
				'canvas' => 1,
				'scope' => 'publish_stream,email, offline_access',
				'ext_perm' =>  'offline_access',
				'display' => 'popup',
			));
			
echo '<div class="facebook"><a href="' . $login_url . '" target="_self"><img src="' . elgg_get_site_url() .'mod/minds_social/graphics/fbx32.png"/></a></div>';

/**
 * Twitter Button
 */
$url = elgg_get_site_url() . 'social/twitter/forward';
$img_url = elgg_get_site_url() .'mod/minds_social/graphics/twitterx32.png';

$login = <<<__HTML
<div class="twitter">
	<a href="$url" target="_self">
		<img src="$img_url" alt="Twitter" />
	</a>
</div>
__HTML;

echo $login;
			
