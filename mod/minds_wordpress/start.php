<?php

elgg_register_event_handler('init','system',function(){
    
    // JS for topbar
    elgg_register_page_handler('minds_topbar', function ($pages) {
        
        switch ($pages[0]) {
            case 'css' : require_once(dirname(__FILE__) . '/pages/topbar_css.php'); break;
            case 'js' : require_once(dirname(__FILE__) . '/pages/topbar_js.php'); break;
        }
        
        return true;
        
    });
    
});	
