<?php

/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

	// Load Elgg engine
		require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");
		gatekeeper();

	// Get the current page's owner
	$owner = elgg_get_page_owner_entity();
	
	gatekeeper();
	group_gatekeeper();
	
	$title = elgg_echo('file:add');
	
	// set up breadcrumbs
	elgg_push_breadcrumb(elgg_echo('file'), "file/all");
	if (elgg_instanceof($owner, 'user')) {
		elgg_push_breadcrumb($owner->name, "file/owner/$owner->username");
	} else {
		elgg_push_breadcrumb($owner->name, "file/group/$owner->guid/all");
	}
	elgg_push_breadcrumb($title);
	
	// create form
	$form_vars = array('enctype' => 'multipart/form-data');
	//$body_vars = file_prepare_form_vars();
	$content = elgg_view_form('kaltura_video/upload', $form_vars, $body_vars);
	
	echo $content;
	
	/*$body = elgg_view_layout('content', array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	));
	
	echo elgg_view_page($title, $body);*/

