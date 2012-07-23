<?php


	// Display form
	$title = elgg_echo('cms_cancel_account:cancelaccount'); // set the title	
	$content = elgg_view("cms_cancel_account/form"); //Get the form
		

	$params = array(
		'content' => $content,
		'title' => $title,
	);
		
	$body = elgg_view_layout('one_column', $params);
		
	// Draw page
	echo elgg_view_page(elgg_echo('cms_cancel_account:cancelaccount'),$body);
		