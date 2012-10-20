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
			));
			
echo '<div><a href="' . $login_url . '"><img src="' . elgg_get_site_url() .'mod/minds_social/graphics/facebook_connect.gif"/></a></div>';
			
