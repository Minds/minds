<?php
/**
 * Minds Notifications API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\notifications\api\v1;

use minds\core;
use minds\interfaces;
use minds\api\factory;

class notifications implements interfaces\api{

    /**
     * Return a list of notifications
     * @param array $pages
     * 
     * API:: /v1/notifications
     */      
    public function get($pages){
	   $response = array();

	   $db = new \minds\core\data\call('entities_by_time');
       $guids = $db->getRow('notifications:'.$user_guid, array('limit'=> get_input('limit', 5), 'offset'=>get_input('offset','')));
        if(!$guids){
            $response = array();
        } else {
            $notifications = core\entities::get(array('guids'=>$guids));
            $response['notifications'] = factory::exportable($notifications);
            foreach($response['notifications'] as $k => $data){
                $owner = new \minds\entities\user($data['owner_guid']);
                $from = new \minds\entities\user($data['from_guid']);
                $entity = \minds\core\entities::build(new \minds\entities\entity($data['object_guid']));
                $response['notifications'][$k]['ownerObj'] = $owner->export();
                $response['notifications'][$k]['fromObj'] = $from->export();
                $response['notifications'][$k]['entityObj'] = $entity->export();
            }
        }

        return factory::response($response);
        
    }
    
    /**
     * Not supported
     */
    public function post($pages){}
    
    /**
     * Not supported
     */
    public function put($pages){}
    
    /**
     * Not supported
     */
    public function delete($pages){}
 
}
        
