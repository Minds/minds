<?php
/**
 * Conversation model
 */
 
namespace minds\plugin\gatherings\entities;

use minds\core\data;
use minds\entities\object;

class conversation{
	
	public $participants = array();
	public $ts = 0;
	
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
	public function update($count = 1){
		//now update this message as being the last message for the conversation. this is the list of users conversations
		$indexes = new data\indexes();
		
		$keys = array();
		$i = 0;
		while($i < count($this->participants)){
			$user_guid = $this->participants[$i];
			
			foreach($this->participants as $key => $participant){
				if($user_guid != $participant){
					$indexes->insert("object:gathering:conversations:$user_guid", array($participant=> json_encode(array(
							'ts'=>time(), 
							'unread'=> $participant == elgg_get_logged_in_user_guid() ? $count : 0, 
							'participants'=>$this->participants
					))));
					//create an index so we can see the unread messages.. reset on each view of the messages
					$indexes->insert("object:gathering:conversations:unread", array($participant=> $count));
				}
			}
			
			$i++;
		}

	}
	
	public function clearCount(){
		$indexes = new data\indexes();
		foreach($this->participants as $key => $participant){
			$indexes->insert("object:gathering:conversations:".elgg_get_logged_in_user_guid(), array($participant=> json_encode(array(
					'ts'=>time(), 
					'unread'=>0, 
					'participants'=>$this->participants
			))));
		}
	}
		
	/**
	 * Return the index keys for the messages to be stored under
	 */
	public function getIndexKeys(){
		return self::permutate($this->participants);
	}
	
	static public function permutate($input){
		
		if(1 === count($input))
			return $input;

		$result = array();
		foreach($input as $key => $item)
			foreach(self::permutate(array_diff_key($input, array($key => $item))) as $p)
				$result[] = "$item:$p";
		
		return $result;

	}
	
	public function save($timebased = true){
		return true;
	}
	
}
