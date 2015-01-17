<?php
/**
 * Gatherings page handler
 */
namespace minds\plugin\gatherings\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\gatherings\entities;

class gatherings extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	
	/**
	 * Reading messages and getting lists of messages
	 */
	public function get($pages){
		exec("which gpg", $output);
		var_dump($output);
		exit;
		//get a list of all chats, regardless
		//we maintain a list of a users 'recent chats' with key=>time and value=>gathering_guid
		//the gathering model is responsible for REMOVING and ADDING this data
		$indexes = new core\data\indexes('object:gatherings');
		$guids = $indexes->get(\elgg_get_logged_in_user_guid() . ":recent");
		if(!$guids){
			//the user doesn't have any recent chats. maybe show them a list of their subscriptions?
		}
		$gatherings = elgg_get_entities(array('subtype'=>'gatherings', 'guids'=>$guids));
		
		switch($pages[0]){
			case is_numeric($pages[0]):
				$gathering = new entities\gathering($pages[0]);
				
				break;
			case 'list':
			default:
				
		}
		
		$layout = elgg_view_layout('content', array('content'=>$content));
		$this->render($layout);
		
	}
	
	/**
	 * Posting messages 
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
	
	/**
	 * Uploading content via messages (coming soon)
	 */
	public function put($pages){}
	
	/**
	 * Deleting messages
	 */
	public function delete($pages){
		$comment = new entities\comment($pages[0]);
		if($comment->delete())
			echo 'true';
		else 
			echo false;
	}
	
}
