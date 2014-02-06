<?php
/**
 * 
 */
class MindsControlTicket extends ElggObject{
	
	static $asana_api_key = 'ltdz6XZ.iDHoyREl6B2JjpuqAHfp6HjE';
	static $asana_workspace = "1229619425513";
	static $asana_project = "2830405850223"; //@todo make this dynamic
	static $asana;
	
	/**
	 * The construct
	 * 
	 * @param int $guid - the guid of the ticket
	 */
	public function __construct($guid = NULL){
		parent::__construct($guid);
		$this->setupAsana();
	}
	
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes['subtype'] = "control_ticket";
	}
	
	public function setupAsana(){
		elgg_load_library('asana');
		
		self::$asana = new Asana(self::$asana_api_key);
		//var_dump(self::$asana->getWorkspaces());
		
	}
	
	public function createAsanaTask(){  
		$result = self::$asana->createTask(array(
			"workspace" => self::$asana_workspace, // Workspace ID
			"name" => "$this->title (via control api)", // Name of task
			"notes" => $this->description,
			"assignee" => "mark@minds.com" // Assign task to...
		));
		
		// As Asana API documentation says, when a task is created, 201 response code is sent back so...
		if(self::$asana->responseCode == "201" && !is_null($result)){
		
			$result = json_decode($result);
		
			$taskId = $result->data->id; // Here we have the id of the task that have been created
		
			$this->asanaTaskId = $taskId;
			
			self::$asana->addProjectToTask($taskId, self::$asana_project);
			self::$asana->commentOnTask($taskId, "Ticket created via Control API by ". $this->getOwnerEntity()->username);
			self::$asana->addTagToTask($taskId, "control:ticket:guid:$this->guid");
			
			$this->save();
			
		} else {
			//echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
		}
	}
	
	function save(){
		$guid = create_entity($this);
		$this->attributes['guid'] = $guid;
		
		if(!isset($this->asanaTaskId)){
			$this->createAsanaTask();
		}
		return $guid;
	}
	
	function comment($message = "", $user_guid){
		$user = get_entity($user_guid); 
		self::$asana->commentOnTask($this->asanaTaskId, "$message via $user->username");
	}
	
}
