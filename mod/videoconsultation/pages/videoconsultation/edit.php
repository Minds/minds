<?php

	// Load Elgg engine
		require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");

	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward();
    
    $title = elgg_echo('videoconsultation:edit');
    elgg_push_breadcrumb($title);

    $videoconsultation_guid = get_input('guid');
    $videoconsultation = get_entity($videoconsultation_guid);

	// choose the required canvas layout and items to display
	  $title = elgg_view_title(elgg_echo('videoconsultation:edit'));

    $vars = videoconsultation_prepare_form_vars($videoconsultation);
    $content = elgg_view_form('videoconsultation/edit', array(), $vars);

    $body = elgg_view_layout('content', array(
      	'filter' => '',
      	'content' => $content,
      	'title' => $title,
    ));
  
  
  	// Display page
    echo elgg_view_page(elgg_echo('videoconsultation:edit'),$body);

?>
