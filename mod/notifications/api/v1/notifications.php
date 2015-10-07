<?php
/**
 * Minds Notifications API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\notifications\api\v1;

use Minds\Core;
use minds\interfaces;
use Minds\Api\Factory;

class notifications implements Interfaces\api{

    /**
     * Return a list of notifications
     * @param array $pages
     * 
     * API:: /v1/notifications
     */      
    public function get($pages){
	   $response = array();

	   $db = new \Minds\Core\Data\Call('entities_by_time');
       $guids = $db->getRow('notifications:'.elgg_get_logged_in_user_guid(), array('limit'=> get_input('limit', 5), 'offset'=>get_input('offset','')));
       if(get_input('offset')){
        array_shift($guids);
       }
       if(!$guids){
            $response = array();
        } else {
            $notifications = core\Entities::get(array('guids'=>$guids));
            $response['notifications'] = factory::exportable($notifications);
            foreach($response['notifications'] as $k => $data){
                $owner = new \Minds\Entities\User($data['owner_guid']);
                $from = new \Minds\Entities\User($data['from_guid']);
                $entity = \Minds\Core\Entities::build(new \Minds\Entities\Entity($data['object_guid']));
                $response['notifications'][$k]['ownerObj'] = $owner->export();
                $response['notifications'][$k]['fromObj'] = $from->export();
		        $response['notifications'][$k]['fromObj']['guid'] = (string) $from->guid;
		        $response['notifications'][$k]['from_guid'] = (string) $from->guid;
                if($entity){
                    $response['notifications'][$k]['entityObj'] = $entity->export();
                    $response['notifications'][$k]['entityObj']['guid'] = (string) $entity->guid;
                }
            }
		    $response['load-next'] = (string) end($notifications)->guid;
		    $response['load-previous'] = (string) key($notifications)->guid;
        }

        return Factory::response($response);
        
    }
    
    /**
     * Not supported
     */
    public function post($pages){
//        if(!Core\Session::isLoggedIn()){
  //         header("HTTP/1.1 401 Unauthorized");
    //        error_log('not logged in, but trying to register push notification id');
      //     exit;
      //  }
       
        $service = $_POST['service'];
        $device_id = $_POST['token'];
        
        //register the push notification
        $token = \Surge\Token::create(array(
                    'service'=>$service,
                    'token'=>$device_id
                    ));
       
        $user_guid = Core\Session::getLoggedinUser()->guid;
        $db = new Core\Data\Call('entities');
        $db->insert($user_guid, array('surge_token' => $token));
        
        return Factory::response($response);
    }
    
    /**
     * Not supported
     */
    public function put($pages){}
    
    /**
     * Not supported
     */
    public function delete($pages){}
 
}
        
