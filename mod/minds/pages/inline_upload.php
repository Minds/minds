<?php

	// Load Elgg engine
	gatekeeper();

	// Get the current page's owner
	$owner = elgg_get_page_owner_entity();
	
	gatekeeper();
	group_gatekeeper();
	
	$title = elgg_echo('file:add');
	
	
	// create form
	$form_vars = array('enctype' => 'multipart/form-data', 'action'=> elgg_get_site_url() == 'http://www.minds.com/' ? 'http://upload.minds.com' : 'action/upload');
	//$body_vars = file_prepare_form_vars();
	$content = elgg_view_form('minds/upload', $form_vars, $body_vars);
	
	echo $content;

