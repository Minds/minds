<?php

	// Load Elgg engine
	require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");

	elgg_set_context('news');
	$video_id = get_input('video_id');
	echo kaltura_create_generic_widget_html ( $video_id , 'news' );
	