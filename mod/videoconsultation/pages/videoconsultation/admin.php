<?php

	// Load Elgg engine
		require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");

	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward();

    $ver=explode('.', get_version(true));			
    if ($ver[1]>7) {
      $title = elgg_echo('videoconsultation:admin');
      elgg_push_breadcrumb($title);
    }

	// choose the required canvas layout and items to display
	    $area2 = elgg_view_title(elgg_echo('videoconsultation:setting'));
	    $area2 .= elgg_view("videoconsultation/forms/setting");
    	if ($ver[1]>7) {
        $sidebar = elgg_view('videoconsultation/sidebar');
        $body = elgg_view_layout("one_sidebar", array(
           'content' => $area2,
           'sidebar' => $sidebar
        ));
    } else {
      $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
    }

	// Display page
  	if ($ver[1]>7) echo elgg_view_page(elgg_echo('videoconsultation:setting'),$body, 'default', array( 'sidebar' => $sidebar ));
    else page_draw(elgg_echo('videoconsultation:setting'),$body);

?>
