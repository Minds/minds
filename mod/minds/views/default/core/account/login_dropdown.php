<?php
/**
 * Elgg drop-down login form
 */

if (elgg_is_logged_in()) {
	return true;
}

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace("http:", "https:", elgg_get_site_url());
}

$body = elgg_view_form('login', array('action' => "{$login_url}action/login"));
?>

<div id="login-dropdown">
	<?php 
	
		echo elgg_view('output/url', array(
			'href' => 'login#login-dropdown-box',
			//'rel' => 'popup',
			'class' => 'login-button entypo',
			'text' => '&#59399;',
			
		)); 
		echo elgg_view_module('dropdown', '', $body, array('id' => 'login-dropdown-box')); 
	?>
</div>
