<?php

elgg_register_event_handler('init','system', function(){	
	$wp = new minds\plugin\minds_wordpress\minds_wordpress();
},900);
  
    // Widget endpoints
/*    elgg_register_page_handler('minds_wp', function ($pages) {
       
        switch ($pages[0]) {
            
            case 'featured' : 
                require_once(dirname(__FILE__) . '/pages/widgets/featured.php'); return true;
                break;
            case 'topbar' :
                require_once(dirname(__FILE__) . '/pages/widgets/topbar.php'); return true;
                break;
        }
        
        return false;
    });*/

