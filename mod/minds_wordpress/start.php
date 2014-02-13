<?php

elgg_register_event_handler('init','system',function(){
    
    // JS for topbar
    elgg_register_page_handler('minds_topbar_js', function ($pages) {
        
        
        require_once(dirname(__FILE__) . '/pages/topbar_js.php');
        
    });
    
});	
