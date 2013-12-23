<?php
/**
 * ElggNotificationEmail Class
 *
 */
class ElggNotificationEmail extends ElggNotification {

	/**
	 * Set type to notifications
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'email';
	}

	/**
	 * Load or create  new
	 *
	 * @param mixed $guid If an int, load that GUID.  If a db row, then will attempt to
	 * load the rest of the data.
	 *
	 * @throws IOException If passed an incorrect guid
	 * @throws InvalidParameterException If passed an Elgg* Entity that isn't an ElggObject
	 */
	function __construct($guid = null) {
		$this->initializeAttributes();

		if (!empty($guid)) {
			// Is $guid is a DB row from the entity table
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this -> load($guid)) {
					$msg = elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid -> guid));
					throw new IOException($msg);
				}
			} else {
				if (!$this -> load($guid)) {
					throw new IOException(elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid)));
				}
			}
		}
	}


	public function save() {
		//some special logic as this is not an enitiy... or should it be?
		$guid = create_entity($this, 'false');

		return $guid;
	}
	
	public function getRecipients($limit = 100, $offset= ""){
		global $DB;
		//return elgg_get_entities(array('type'=>'user', 'limit'=>$limit));
		//GET THE GUIDS OF ALL SUBSCRIBED
		try{
			
			$slice = new phpcassa\ColumnSlice($this->last_sent ?: $offset, "", $limit, false);
			$guids = $DB->cfs['entities_by_time']->get('notification:subscriptions:'.$this->subscription, $slice);	
			unset($guids[$this->last_sent]);

			if(count($guids) > 0){
				$recipients = elgg_get_entities(array('type'=>'user','guids'=>array_values($guids)));
			}

		}catch(Exception $e){
			return null;
		}
			
		return $recipients;
	}
	
	public function setState($state){
		$this->state = $state;
		$this->save();
	}
	
	private function getTemplate(array $vars = array()){
		return elgg_view_entity($this, $vars);
	}
	
	public function send($limit = 100){
		
		set_time_limit(0);

		if($this->state == 'running' && $this->state == 'complete'){
			return false;	
		}
		
		$this->setState('running');
	
		$recipients = $this->getRecipients($limit, $this->last_sent);
		if(!$recipients){
			$this->setState('complete'); 
			return;
		}
		
		foreach($recipients as $recipient){
			$template = $this->getTemplate(array('recipient'=>$recipient));
			phpmailer_send('mark@minds.com', 'Marks Node', $recipient->email, $recipient->name, $this->subject, $template, null, true);
		}
		
		$this->last_sent = end($recipients)->guid;
		
		$this->setState('waiting'); 
		//$this->delete();
		return true;
	}

	public function delete(){
		return db_remove($this->guid, $this->type);
	}
}
