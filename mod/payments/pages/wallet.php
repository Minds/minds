<?php

namespace minds\plugin\payments\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\payments;

class wallet  extends core\page implements interfaces\page{
    
    
    /**
     * Get requests
     */
    public function get($pages){
       
        $content = elgg_view('wallet/dashboard', array('count'=> \Minds\Helpers\Counters::get(Core\session::getLoggedinUser()->guid, 'points', false)));
        $body = \elgg_view_layout('one_column', array('title'=>\elgg_echo('wallet'), 'content'=>$content));
        
        echo $this->render(array('body'=>$body));
        
    }
    
    /**
     * Accept adding new cards @todo
     */
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}

