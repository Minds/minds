<?php

	// Load Elgg engine
	gatekeeper();

	// Get the current page's owner
	$owner = elgg_get_page_owner_entity();
	
	gatekeeper();
	group_gatekeeper();
	
	$title = elgg_echo('file:add');
	
	
	// create form
	$form_vars = array('enctype' => 'multipart/form-data');
	//$body_vars = file_prepare_form_vars();
	$content = elgg_view_form('archive/upload', $form_vars, $body_vars);
	
	$body = elgg_view_layout("one_column", array(
						'content' => $content, 
						'sidebar' => false,
						'title' => elgg_echo('archive:upload:others')
						));
						
	// Display page
	echo elgg_view_page(elgg_echo('archive:upload:others'),$body);


