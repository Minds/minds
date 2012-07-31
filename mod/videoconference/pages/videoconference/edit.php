<?php

	// Load Elgg engine
		require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");

	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward();
    
    $title = elgg_echo('videoconference:edit');
    elgg_push_breadcrumb($title);

    $videoconference_guid = get_input('guid');
    $videoconference = get_entity($videoconference_guid);

	// choose the required canvas layout and items to display
	  $title = elgg_view_title(elgg_echo('videoconference:edit'));

    $vars = videoconference_prepare_form_vars($videoconference);
    $content = elgg_view_form('videoconference/edit', array(), $vars);

    $body = elgg_view_layout('content', array(
      	'filter' => '',
      	'content' => $content,
      	'title' => $title,
    ));
  
  
  	// Display page
    echo elgg_view_page(elgg_echo('videoconference:edit'),$body);

?>
