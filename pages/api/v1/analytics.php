<?php
/**
 * Minds Analytics Api endpoint
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use Minds\Api\Factory;

class analytics implements interfaces\api{

    public function get($pages){
    }
    
    public function post($pages){
    }
    
    /**
     * Sets an analytic 
     * @param array $pages
     */
    public function put($pages){
        switch($pages[0]){
            case 'open':
                error_log("analytics setting " . Core\session::getLoggedinUser()->guid);
                $db = new Core\Data\Call('entities_by_time');
                $db->insert("analytics:open", array(Core\session::getLoggedinUser()->guid => time()), 300);
            break;
        }

        return Factory::response(array());
    }
    
    public function delete($pages){
    }
    
}
        
