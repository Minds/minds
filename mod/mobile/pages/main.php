<?php

	/**
	 * main page by mark harding
	 * 
	 */

if (!elgg_is_logged_in()) {
$params = elgg_view('core/account/login_box');

$body = elgg_view_layout('one_column', $params);



echo elgg_view_page($title,$body);
	
} else {
	forward("activity");
}
?>