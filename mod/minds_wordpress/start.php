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
    
    // Listen for new comments
    elgg_register_event_handler('comment:create', 'comment', function($event, $object_type, $data) {
        
        global $CONFIG;
        
        // Ping our comment at our linked server permalink, the server then uses the API to verify the comment before posting
        if (($data['_source']['pid']) && ($post = get_entity($data['_source']['pid'], 'object'))) {
                    
            // Ok we've got a post, does it have a remote permalink?
            if ($permalink = $post->ex_permalink) {
                
                // Ping data to the minds wordpress plugin

                
                $query = http_build_query($data);

                if (strpos($permalink, '?') === false) // Tell the wordpress blog that we're pinging with a comment action
                    $permalink .= '?minds-connect=comment-on';
                else
                    $permalink .= '&minds-connect=comment-on';
                
                
                
                $ch = curl_init();
                
                curl_setopt($ch,CURLOPT_URL, $permalink);
                curl_setopt($ch,CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $query);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Minds Site at " . elgg_get_site_url());
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

                //execute post
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $result = curl_exec($ch);
                if ($CONFIG->debug)
                    error_log("Result from pinging $permalink: $http_status");
                
                curl_close($ch);
            }
            
        }
        
    });
    
    
});	
