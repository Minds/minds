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
	
	/**
	 * Update the users own list of active conversations, along with a timestamp
	 */
	public function update(){
		//now update this message as being the last message for the conversation. this is the list of users conversations
		$indexes = new data\indexes();
		
		$keys = array();
		$i = 0;
		while($i < count($this->participants)){
			$user_guid = $this->participants[$i];
			
			foreach($this->participants as $key => $participant){
				if($user_guid != $participant)
					$indexes->insert("object:gathering:conversations:$user_guid", array($participant=> time()));
			}
			
			$i++;
		}

	}
		
	/**
	 * Return the index keys for the messages to be stored under
	 */
	public function getIndexKeys(){
		$o = $this->participants;
		array_walk($o, 
				function(&$user_guid, $key, $participants){
					$i =  "$user_guid";
					foreach($participants as $participant){
						if($user_guid != $participant){
							$i .= ":$participant";
						}
					}
					$user_guid = $i;
				},
				$this->participants
			);
		return $o;
	}
	
	public function save($timebased = true){
		return true;
	}
	
}
