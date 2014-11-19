<?php
/**
 * Conversation model
 */
 
namespace minds\plugin\gatherings\entities;

use minds\core\data;
use minds\entities\object;

class conversation{
	
	public $participants = array();
	
	/**
	 * Construct a conversation 
	 * 
	 * @param ... user_guid, user_guid, user_guid
	 */
	public function __construct(){
		$user_guids = func_get_args();
		$this->participants = $user_guids;
	}
	
	public function createMessage(){
		$message = new message();
		$message->setRecipients($this->participants);
		return $message;
	}
	
	public function getMessages($limit = 12, $offset = ''){
		
	}
	
	public function save($timebased = true){
		return true;
	}
	
}
