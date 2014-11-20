<?php
/**
 * Gatherings page handler
 */
namespace minds\plugin\gatherings\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;

class conversation extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	
	/**
	 * Reading messages and getting lists of messages
	 */
	public function get($pages){
		
		$encrypted = FALSE;
		$user = new \minds\entities\user($pages[0]);
		
		if(\elgg_get_plugin_user_setting('publickey', $user->guid, 'gatherings'))
				$encrypted = TRUE;
		
		$convseration = new entities\conversation(elgg_get_logged_in_user_guid(), $user->guid);
		$a = elgg_get_logged_in_user_guid();
		$b = $user->guid;
		$guids = core\data\indexes::fetch("object:gathering:conversation:$a:$b");
		$messages = core\entities::get(array('guids'=>$guids));
		foreach($messages as $k => $message){
			$messages[$k] = new entities\message($message);
			//var_dump($message->decryptMessage());
		}
		$messages = array_reverse($messages);
		
		$conversation_guids = core\data\indexes::fetch("object:gathering:conversations:".elgg_get_logged_in_user_guid());
		if($conversation_guids){
			$convserations = array();
			foreach($conversation_guids as $user_guid => $ts){
				$user = new \minds\entities\user($user_guid);
				if($user->username){
					$conversations[] = $user;
				}
			}
		}
			
		$content = elgg_view('gatherings/conversation', array('conversation'=>$conversation, 'messages'=>$messages));
		$content .= elgg_view_form('conversation', array('action'=>elgg_get_site_url() . 'gatherings/conversation/'.$user->guid), array('encrypted'=>$encrypted,'user'=>$user));
		
		$layout = elgg_view_layout('one_sidebar_alt', array('content'=>$content, 'sidebar'=>elgg_view('gatherings/conversations/list', array('conversations'=>$conversations))));
		echo $this->render(array('body'=>$layout));
		
	}
	
	/**
	 * Posting messages 
	 */
	public function post($pages){
		
		$conversation = new entities\conversation(elgg_get_logged_in_user_guid(), get_input('user_guid'));
		
		$message = new entities\message($conversation);
		$message->setMessage(get_input('message'))
				->save();
				
		$this->forward(REFERRER);
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
