<?php

	/**
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 */
	// Load Elgg engine
		require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
    global $CONFIG;

    $ver=explode('.', get_version(true));
  	if ($ver[1]>7) {
      elgg_pop_breadcrumb();
      elgg_push_breadcrumb(elgg_echo('livestreaming'));
    }

	$title = elgg_view_title(elgg_echo("livestreaming:rooms"));
	
      $options = elgg_list_entities(array(
      	'type' => 'object',
      	'subtype' => 'livestreaming',
      	'limit' => 10,
      	'full_view' => false,
      	'view_toggle_type' => false
      ));
    
  	
      $sidebar = elgg_view('livestreaming/sidebar');
      $body = elgg_view_layout('content', array(
      	'filter_context' => 'all',
      	'content' => $options,
      	'title' => $title,
      	'sidebar' => $sidebar,
      ));

	// Display page
 	echo elgg_view_page(elgg_echo('livestreaming:rooms'),$body, 'default', array( 'sidebar' => "" ));
 
