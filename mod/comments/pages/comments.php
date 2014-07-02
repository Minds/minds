<?php
/**
 * Notifications page handler
 */
namespace minds\plugin\comments\pages;

use minds\core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\comments\entities;

class comments extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		$type = $pages[0];
		$parent_guid = $pages[1];
		$entity = get_entity($parent_guid);
		
		$indexes = new \minds\core\data\indexes('comments');
		$guids = $indexes->get($entity->guid, array('limit'=>\get_input('limit',3), 'offset'=>\get_input('offset',''), 'reversed'=>true));
		if($guids)
			$comments = \elgg_get_entities(array('guids'=>$guids, 'limit'=>\get_input('limit',3), 'offset'=>\get_input('offset','')));
		else 
			$comments = array();

		usort($comments, function($a, $b){ return $a->time_created - $b->time_created;});
		
		echo elgg_view('comments/list', array('comments'=>$comments));
		
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
		
			\elgg_trigger_plugin_hook('notification', 'all', array(
				'to' => array($parent_entity->owner_guid),
				'object_guid'=>$parent_guid,
				'description'=>$desc,
				'notification_view'=>'comment'
			));
			
			\elgg_trigger_event('comment:create', 'comment', $data); 
			
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
		if($comment->delete())
			echo 'true';
		else 
			echo false;
	}
	
}
