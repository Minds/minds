<?php
/**
 * Notifications page handler
 */
namespace minds\plugin\comments\pages;

use Minds\Core;
use Minds\Core\data;
use Minds\Interfaces;
//use minds\plugin\comments;
use minds\plugin\comments\entities;

class comments extends core\page implements Interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		$type = $pages[0];
		$parent_guid = $pages[1];
		$entity = get_entity($parent_guid);
		
		$indexes = new \Minds\Core\Data\indexes('comments');
		$guids = $indexes->get($entity->guid, array('limit'=>\get_input('limit',3), 'offset'=>\get_input('offset',''), 'reversed'=>true));
		if(isset($guids[get_input('offset')]))
			unset($guids[get_input('offset')]);

		if($guids)
			$comments = \elgg_get_entities(array('guids'=>$guids, 'limit'=>\get_input('limit',3), 'offset'=>\get_input('offset','')));
		else 
			$comments = array();

		usort($comments, function($a, $b){ return $a->time_created - $b->time_created;});
		
		if ($comments)
		    echo elgg_view('comments/list', array('comments'=>$comments));
		else
		    http_response_code (404);
		
	}
	
	/**
	 * Post comments
	 */
	public function post($pages){
		$type = $pages[0];
		$parent_guid = $pages[1];
		$parent_entity = \get_entity($parent_guid);
		$ia = \elgg_set_ignore_access(true);
		
		$desc = $_POST['comment'];
		
		if (!\elgg_is_logged_in()){
			exit;	
			//relies on the minds user account being created @todo fix this?
			$owner = new \ElggUser('minds');
		
			if (false !== strpos($desc, 'http')){
				exit; //most probably spam
			}
		
		}else {
			$owner = elgg_get_logged_in_user_entity();
		}
		
		$comment = new entities\comment();
		$comment->description = $desc;
		$comment->parent_guid = $parent_guid;
		if($comment->save()){
			
			$subscribers = Data\indexes::fetch('comments:subscriptions:'.$parent_entity->guid) ?: array();
			$subscribers[$parent_entity->owner_guid] = $parent_entity->owner_guid;
			if(isset($subscribers[$comment->owner_guid]))
				unset($subscribers[$comment->owner_guid]);
            
            $subscribers = array_unique($subscribers);
		    \elgg_trigger_plugin_hook('notification', 'all', array(
				'to' => $subscribers,
				'object_guid'=>$parent_guid,
				'description'=>$desc,
				'notification_view'=>'comment'
			));
           	
			\elgg_trigger_event('comment:create', 'comment', $data); 
			
			$indexes = new Data\indexes();
			$indexes->set('comments:subscriptions:'.$parent_entity->guid, array($comment->owner_guid => $comment->owner_guid));
			
			\elgg_set_ignore_access($ia);
			
			echo "<li class=\"minds-comment\">";
			echo $comment->view();
			echo "</li>";
		}
		exit;
	}
	
	public function put($pages){}
	
	public function delete($pages){
        $comment = new entities\comment($pages[0]);
        if($comment->canEdit()){
		    if($comment->delete())
			    echo 'true';
		    else 
                echo false;
        }
	}
	
}
