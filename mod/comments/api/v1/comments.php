<?php
/**
 * Minds Comments API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\comments\api\v1;

use Minds\Core;
use Minds\Core\Data;
use minds\interfaces;
use Minds\Api\Factory;

class comments implements interfaces\api{

    /**
     * Returns the comments
     * @param array $pages
     *
     * API:: /v1/comment/:guid
     */
    public function get($pages){

        $response = array();
        $guid = $pages[0];

        $indexes = new core\Data\indexes('comments');
        $guids = $indexes->get($guid, array('limit'=>\get_input('limit',3), 'offset'=>\get_input('offset',''), 'reversed'=>\get_input('reversed', false)));
        if(isset($guids[get_input('offset')]))
            unset($guids[get_input('offset')]);

        if($guids)
            $comments = \elgg_get_entities(array('guids'=>$guids));
        else
            $comments = array();

        usort($comments, function($a, $b){
          return $a->time_created - $b->time_created;
        });
  	    foreach($comments as $k => $comment){
            if(!$comment->guid){
                unset($comments[$k]);
                continue;
            }
  		      $owner = $comment->getOwnerEntity();
  		      $comments[$k]->ownerObj = $owner->export();
  	    }
        $response['comments'] = factory::exportable($comments);
        $response['load-next'] = (string) end($comments)->guid;
        $response['load-previous'] = (string) reset($comments)->guid;

        return Factory::response($response);

    }

    public function post($pages){

        $parent = new \Minds\entities\entity($pages[0]);
        $comment = new \Minds\plugin\comments\entities\comment();
        $comment->description = urldecode($_POST['comment']);
        $comment->parent_guid = $pages[0];
        if($comment->save()){
            $subscribers = Data\indexes::fetch('comments:subscriptions:'.$pages[0]) ?: array();
            $subscribers[$parent->owner_guid] = $parent->owner_guid;
            if(isset($subscribers[$comment->owner_guid]))
                unset($subscribers[$comment->owner_guid]);

	          \Minds\plugin\payments\start::createTransaction(Core\Session::getLoggedinUser()->guid, 1, $pages[0], 'comment');

            \elgg_trigger_plugin_hook('notification', 'all', array(
                'to' => $subscribers,
                'object_guid'=>$pages[0],
                'description'=>$comment->description,
                'notification_view'=>'comment'
            ));

            \elgg_trigger_event('comment:create', 'comment', $data);

            $indexes = new data\indexes();
            $indexes->set('comments:subscriptions:'.$parent->guid, array($comment->owner_guid => $comment->owner_guid));
        }
        $comment->ownerObj = Core\Session::getLoggedinUser()->export();
        $response['comment'] = $comment->export();

        return Factory::response($response);
    }

    public function put($pages){

        return Factory::response(array());

    }

    public function delete($pages){


        $comment = new \Minds\plugin\comments\entities\comment($pages[0]);
        if($comment->canEdit()){
            $comment->delete();
        }

        return Factory::response(array());

    }

}
