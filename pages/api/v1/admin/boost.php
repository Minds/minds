<?php
/**
 * Minds Admin: Boosts
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace minds\pages\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class boost implements Interfaces\Api{

    private $rate = 1;

    /**
     * Returns a list of boosts
     */
    public function get($pages){
        $response = array();

    	switch($pages[0]){
    	    case is_numeric($pages[0]):
    	        $entity = entities\Factory::build($pages[0]);
    		    $response['entity'] = $entity->export();
        		//going to assume this is a channel only review for now
    	        $boost_ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
        		$guids = $boost_ctrl->getReviewQueue(1, $pages[0]);
                if(!$guids || key($guids) != $pages[0]){
    	    	    return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        		}
    	    	$response['points'] = reset($guids);
    	    break;
    	    case "rates":
              $response['balance'] = (int) Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false);
    	        $response['rate'] = $this->rate;
              $response['cap'] = 800;
              $response['min'] = 5;
    	    break;
            case "p2p":
                $db = new Core\Data\Call('entities_by_time');
                $queue_guids = $db->getRow("boost:channel:" . Core\Session::getLoggedinUser()->guid  . ":review");
                if($queue_guids){
                    $entities =  core\Entities::get(array('guids'=>array_keys($queue_guids)));
                    foreach($entities as $guid =>$entity){
                        $entities[$guid]->points = $queue_guids[$entity->guid];
                    }
                    $response['boosts'] = factory::exportable($entities, array('points'));

                }
                break;
    	}

        return Factory::response($response);
    }

    /**
     * Boost an entity
     * @param array $pages
     *
     * API:: /v1/boost/:type/:guid
     */
    public function post($pages){

        if(!isset($pages[0]))
             return Factory::response(array('status' => 'error', 'message' => ':type must be passed in uri'));

        if(!isset($pages[1]))
            return Factory::response(array('status' => 'error', 'message' => ':guid must be passed in uri'));

        if(!isset($_POST['impressions']))
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be sent in post body'));

        //if($_POST['impressions'] != round($_POST['impressions']))
        //    return Factory::response(array('status' => 'error', 'message' => 'impressions must be a whole number'));

        $_POST['impressions'] = round($_POST['impressions']);
        if((!isset($_POST['destination']) || $_POST['destination'] == '') && round($_POST['impressions']) == 0)
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be a whole number'));

        $response = array();
	    if(Core\Boost\Factory::build(ucfirst($pages[0]), array('destination'=>isset($_POST['destination']) ? $_POST['destination'] : NULL))->boost($pages[1], $_POST['impressions'])){
            //dont use rate for p2p boosts
            if(isset($_POST['destination']) && $_POST['destination'])
                $points = 0 - $_POST['impressions'];
            else
                $points = 0 - ($_POST['impressions'] / $this->rate); //make it negative

            \Minds\plugin\payments\start::createTransaction(Core\Session::getLoggedinUser()->guid, $points, $pages[1], "boost");
            //a boost gift
            if(isset($pages[2]) && $pages[2] != Core\Session::getLoggedinUser()->guid){
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($pages[2]),
                'object_guid' => $pages[1],
                'notification_view' => 'boost_gift',
                'params' => array('impressions'=>$_POST['impressions']),
                'impressions' => $_POST['impressions']
                ));
            } elseif($pages[0] != 'channel') {
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array(Core\Session::getLoggedinUser()->guid),
                'object_guid' => $pages[1],
                'notification_view' => 'boost_submitted',
                'params' => array('impressions'=>$_POST['impressions']),
                'impressions' => $_POST['impressions']
                ));
            }
        } else {
	        $response['status'] = 'error';
        }

        return Factory::response($response);

    }

    /**
     * Called when a boost is to be accepted (assume channels only right now
     * @param array $pages
     */
    public function put($pages){
	    //validate the points
    	$ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
	    $guids = $ctrl->getReviewQueue(1, $pages[0]);
	    if(!$guids){
            return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        }
	    $points = reset($guids);
        \Minds\plugin\payments\start::createTransaction(Core\Session::getLoggedinUser()->guid, $points, $pages[0], "boost (remind)");
	    $accept = $ctrl->accept($pages[0], $points);
	    return Factory::response(array());
    }

    /**
     * Called when a boost is rejected (assume channels only right now)
     */
    public function delete($pages){
	    $ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
        $guids = $ctrl->getReviewQueue(1, $pages[0]);
        if(!$guids){
            return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        }
        $points = reset($guids);
        $entity = new \Minds\Entities\Activity($pages[0]);
        \Minds\plugin\payments\start::createTransaction($entity->owner_guid, $points, $pages[0], "boost refund");
    	$ctrl->reject($pages[0]);
    }

}
