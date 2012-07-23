<?php
	/**
	 * @file views/default/views_counter.php
	 * @brief A common view name for include the views counter system by another views counter plugins
	 */
    global $VIEWS_COUNTER_OVERRIDE_FULL_VIEW;
    
    if(in_array($view_orig, $VIEWS_COUNTER_OVERRIDE_FULL_VIEW)){
      $vars['views_counter_full_view_override'] = TRUE;
    }
    
    if($view_orig == "profile/details"){
      // the entity isn't set yet...
      $vars['entity'] = elgg_get_page_owner_entity();
    }
    
	// Add the views counter to any elgg entity
	echo elgg_view('views_counter/add',$vars);
	
	// Shows the views counter number
	echo elgg_view('views_counter/display_views_counter',$vars);
	