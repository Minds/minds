<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use Minds\Api\Factory;

class newsfeed implements interfaces\api{

    /**
     * Returns the newsfeed
     * @param array $pages
     * 
     * API:: /v1/newsfeed/
     */      
    public function get($pages){
        $response = array();
        
        if(!isset($pages[0]))
            $pages[0] = 'network';
        
        switch($pages[0]){
            case 'single':
                $activity = new \Minds\entities\activity($pages[1]);
                return Factory::response(array('activity'=>$activity->export()));
                break;
            default:
            case 'personal':
        		$options = array(
        			'owner_guid' => isset($pages[1]) ? $pages[1] : elgg_get_logged_in_user_guid()
        		);
        		break;
    	    case 'network':
                $options = array(
                    'network' => isset($pages[1]) ? $pages[1] : core\session::getLoggedInUserGuid()
                );
                break;
        }

        $activity = core\entities::get(array_merge(array(
            'type' => 'activity',
            'limit' => get_input('limit', 5),
            'offset'=> get_input('offset', '')
        ), $options));
        if(get_input('offset')){
            array_shift($activity);
        }

     //   \Minds\Helpers\Counters::incrementBatch($activity, 'impression');
       
        if($pages[0] == 'network'){
            $boost_guid = Core\Boost\Factory::build("Newsfeed")->getBoost();
            if($boost_guid){
                $boost_guid = $boost_guid;
                $boost_object = new entities\activity($boost_guid);
                $boost_object->boosted = true;
                array_unshift($activity, $boost_object);
            }
        }
         
        if($activity){
            $response['activity'] = factory::exportable($activity, array('boosted'));
            $response['load-next'] = (string) end($activity)->guid;
            $response['load-previous'] = (string) key($activity)->guid;
        }
        
        return Factory::response($response);
        
    }
    
    public function post($pages){
        
        //factory::authorize();
        switch($pages[0]){
            case 'remind':
                $embeded = new entities\entity($pages[1]);
                $embeded = core\entities::build($embeded); //more accurate, as entity doesn't do this @todo maybe it should in the future
                \Minds\Helpers\Counters::increment($embeded->guid, 'remind');
                elgg_trigger_plugin_hook('notification', 'remind', array('to'=>array($embeded->owner_guid), 'notification_view'=>'remind', 'title'=>$embeded->title, 'object_guid'=>$embeded->guid));
                \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, 1, $embeded->guid, 'remind');
                \Minds\plugin\payments\start::createTransaction($embeded->owner_guid, 1, $embeded->guid, 'remind');
                $activity = new entities\activity();
                switch($embeded->type){
                    case 'activity':
                        if($embeded->remind_object){
                            $activity->setRemind($embeded->remind_object)->save();
                            \Minds\Helpers\Counters::increment($embeded->remind_object['guid'], 'remind');
                        }else{
                            $activity->setRemind($embeded->export())->save();
                        }      
                     break;
                     default:
                         /**
                           * The following are actually treated as embeded posts.
                           */
                           switch($embeded->subtype){
                               case 'blog':
                                   $message = false;
                                    if($embeded->owner_guid != elgg_get_logged_in_user_guid())
                                        $message = 'via <a href="'.$embeded->getOwnerEntity()->getURL() . '">'. $embeded->getOwnerEntity()->name . '</a>';
                                        $activity->setTitle($embeded->title)
                                        ->setBlurb(elgg_get_excerpt($embeded->description))
                                        ->setURL($embeded->getURL())
                                        ->setThumbnail($embeded->getIconUrl())
                                        ->setMessage($message)
                                        ->setFromEntity($embeded)
                                        ->save();
                                    break;
                            }
                }
            break;
            default:
                $activity = new entities\activity();
                //error_log(print_r($_POST, true)); 
                if(isset($_POST['message']))
                    $activity->setMessage(urldecode($_POST['message']));
                
                if(isset($_POST['title'])){
                        $activity->setTitle($_POST['title'])
                            ->setBlurb($_POST['description'])
                            ->setURL(\elgg_normalize_url($_POST['url']))
                            ->setThumbnail(urldecode($_POST['thumbnail']));
                }
                if($guid = $activity->save()){
                   //Core\Events\Dispatcher::trigger('create', 'activity', $activity);
                    Core\Events\Dispatcher::trigger('social', 'dispatch', array(
                        'services' => array(
                            'facebook' => isset($_POST['facebook']) && $_POST['facebook'] ? $_POST['facebook'] : false,
                            'twitter' => isset($_POST['twitter']) && $_POST['twitter'] ? $_POST['twitter'] : false
                        ),
                        'data' => array(
                            'message' => urldecode($_POST['message']),
                            'perma_url'=> isset($_POST['url']) ? \elgg_normalize_url($_POST['url']) : \elgg_normalize_url($activity->getURL())
                        )
                    ));
                    return Factory::response(array('guid'=>$guid));
                } else {
                    return Factory::response(array('status'=>'failed', 'message'=>'could not save'));
                }
        }
    }
    
    public function put($pages){

        $activity = new entities\activity($pages[0]);
        if(!$activity->guid)
            return Factory::response(array('status'=>'error', 'message'=>'could not find activity post'));

        switch($pages[1]){
            case 'view':
                \Minds\Helpers\Counters::increment($activity->guid, "impression");
                break;
        }

        return Factory::response(array());
        
    }
    
    public function delete($pages){
	$activity = new entities\activity($pages[0]); 
	if(!$activity->guid)
		return Factory::response(array('status'=>'error', 'message'=>'could not find activity post'));      

    if(!$activity->canEdit()){
        return Factory::response(array('status'=>'error', 'message'=>'you don\'t have permission'));
    }

 	if($activity->delete())
        	return Factory::response(array('message'=>'removed ' . $pages[0]));
        	return Factory::response(array('status'=>'error', 'message'=>'could not delete'));
    }
    
}
        
