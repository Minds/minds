<?php

	// Load Elgg engine
	elgg_load_library('archive:kaltura');

	elgg_set_context('news');
	$video_id = get_input('video_id');
	echo kaltura_create_generic_widget_html ( $video_id , 'news' );
	