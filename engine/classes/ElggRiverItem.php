<?php
/**
 * River item class.
 *
 * @package    Elgg.Core
 * @subpackage Core
 * 
 */
class ElggRiverItem {
	
	protected $attributes = array(
		'id' => null,
		'body' => '',
		'action_type' => 'create',
	);
	protected $subject;
	protected $object;
	protected $attachment;

	/**
	 * Construct a river item object given a database row.
	 *
	 * @param stdClass $object Object obtained from database
	 */
	function __construct($id = null) {
		$this->posted = time();
		$this->subject_guid = elgg_get_logged_in_user_guid();
		$this->load($id);
	}
	
	/**
	 * Load a news post
	 */
	public function load($attrs){
		if(is_int($attrs) || is_string($attrs)){
			$db = new Minds\Core\Data\Call('newsfeed');
			$data = $db->getRow($attrs);
			$data['id'] = $attrs;
		}
		
		if(is_array($attrs)){
			$data = $attrs;
		}
		
		if(is_object($attrs)){
			$data = (array) $attrs;
		}
		
		foreach($data as $k => $v){
			$this->$k = $v;
		}
	}

	/**
	 * Get the subject of this river item
	 * 
	 * @return ElggEntity
	 */
	public function getSubjectEntity($brief = true) {
		/*if($brief){
			if($subject = unserialize($this->subject)){
				//cache_entity($subject);
				return $subject;
			}
		}*/
		if(isset($this->subjectObj))
			return entity_row_to_elggstar(json_decode($this->subjectObj), false);
				
		return get_entity($this->subject_guid);
	}

	/**
	 * Get the object of this river item
	 *
	 * @return ElggEntity
	 */
	public function getObjectEntity($brief = true) {
		/*if($brief){
			if($object = unserialize($this->object)){
				cache_entity($object);
				return $object;
			}
		}*/
		if(isset($this->objectObj)){
			return entity_row_to_elggstar(json_decode($this->objectObj));
		}
		return get_entity($this->object_guid);
	}
	
	/**
	 * Set the subject
	 */
	public function setSubject($subject_guid){
		$this->subject = get_entity($subject_guid);
		if($this->subject)
			$this->subjectObj = json_encode($this->subject->export());
	}
	
	/**
	 * Set the object
	 */
	public function setObject($object_guid){
		$this->object = get_entity($object_guid);
		if($this->object)
			$this->objectObj = json_encode($this->object->export());
	}
	
	/**
	 * 
	 */
	public function setattachment($attachment_guid){
		$this->attachment = new ElggFile($attachment_guid);
		
		$this->attachment_path = $this->attachment->getFilenameOnFilestore();
	}

	/**
	 * Get the Annotation for this river item
	 * 
	 * @return ElggAnnotation
	 */
	public function getAnnotation() {
		//return elgg_get_annotation_from_id($this->annotation_id);
	}

	/**
	 * Get the view used to display this river item
	 *
	 * @return string
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * Get the time this activity was posted
	 * 
	 * @return int
	 */
	public function getPostedTime() {
		return (int)$this->posted;
	}

	/**
	 * Get the type of the object
	 *
	 * @return string 'river'
	 */
	public function getType() {
		return 'river';
	}

	/**
	 * Get the subtype of the object
	 *
	 * @return string 'item'
	 */
	public function getSubtype() {
		return 'item';
	}
	
	public function toArray(){
		$array = array();
		foreach($this->attributes as $k => $v){
			if($v) //don't allow null
				$array[$k] = $v;	
		}
		return $array;
	}
	
	private function addToTimelines(){
		$followers = $this->subject->getFriendsOf(null, 10000, "", 'guids');
		if(!$followers) 
			$followers = array();
		
		if($this->access_id == ACCESS_PRIVATE){
			$followers = array(); //don't add to followers timelines if this is private.	
		}
		$followers = array_keys($followers);
		if($this->access_id != ACCESS_PRIVATE){
			array_push($followers, 'site');//add to public timeline
			array_push($followers, $this->action_type);//timelines for actions too
			array_push($followers, $this->subject->guid);//add to their own timeline
			array_push($followers, $this->object->container_guid); //add to containers timeline
		}
		
		if($this->subject->guid == elgg_get_logged_in_user_guid())
			array_push($followers, "personal:" . $this->subject->guid);//add to their personal feed
		
		if(isset($this->to_guid)){
			array_push($followers, $this->to_guid); 
			array_push($followers, "personal:$this->to_guid"); 
		}
		
		if(isset($this->cc) && is_array($this->cc))
			$followers = array_merge($followers, $this->cc);
		
		if(isset($this->timeline_override) && is_array($this->timeline_override))
			$followers = $this->timeline_override; 
				
		foreach($followers as $follower_guid){
			$db = new Minds\Core\Data\Call('timeline');
			$db->insert($follower_guid, array($this->id => time()));
		}
	}
	
	private function removeFromTimelines(){
		$followers = array();
		if($this->subject instanceof ElggUser)
			$followers = $this->subject->getFriendsOf(null, 10000, "", 'guids');

		if(!$followers) 
			$followers = array();
		
		$followers = array_keys($followers);
		array_push($followers, 'site');//add to public timeline
		array_push($followers, $this->action_type);//timelines for actions too
		array_push($followers, $this->subject->guid);//add to their own timeline
		array_push($followers, $this->object->container_guid); //add to containers timeline
		
		if($this->subject->guid == elgg_get_logged_in_user_guid())
			array_push($followers, "personal:" . $this->subject->guid);//add to their personal feed
		
		if(isset($this->to_guid))
			array_push($followers, $this->to_guid); 


		//for featured items, we only want them to appear on the featured line, no others
		if($this->action_type == 'feature'){
			$followers = array($this->action_type);
		}
		
		$db = new Minds\Core\Data\Call('timeline');	
		foreach($followers as $follower_guid){
			if($follower_guid)
				$db->removeAttributes($follower_guid, array($this->id), false);
		}
	}
	
	/**
	 * Save a river post
	 */
	function save(){
		$db = new Minds\Core\Data\Call('newsfeed');
		if(!$this->id){
			$g = new GUID();
			$this->id = $g->generate();
			$this->addToTimeLines();
		}
		error_log($this->id. json_encode($this->toArray()));
		return $db->insert($this->id, $this->toArray());
	}
	
	/**
	 * Delete a river post
	 */
	function delete(){
		$this->removeFromTimelines();
		$db = new Minds\Core\Data\Call('newsfeed');
		$db->removeRow($this->id);
		
	//	$this->removeFromTimelines();
	}
	
	/**
	 * Export an array
	 */
	function export(){
		$export = $this->attributes;
		$export['subjectObj'] = json_decode($export['subjectObj'], true);
		$export['subjectObj']['avatar_url'] = get_entity($export['subjectObj']['guid'])->getIconURL('small');
		$export['objectObj'] = json_decode($export['objectObj'], true);
		$export['friendly_time'] = elgg_get_friendly_time($export['posted']);
		if(isset($export['attachment_guid']))
			$export['attachment_url'] = elgg_get_site_url() . 'photos/thumbnail/'.$export['attachment_guid'].'/large';
		return $export;
	}
	
	/**
	 * Magic set
	 * 
	 * @param string $key 
	 * @param mixed $value
	 * @return void
	 */
	public function __set($key, $value){
		$this->attributes[$key] = $value;
		
		if($key == 'subject_guid')
			$this->setSubject($value);
		
		if($key == 'object_guid')
			$this->setObject($value);
		
	//	if($key == 'attachment_guid')
			//$this->setattachment($value);
		
	}
	
	/**
	 * Magic get
	 * 
	 * @param string $key
	 * @return void
	 */
	public function __get($key){
		if(isset($this->attributes[$key]))
			return $this->attributes[$key];
	}
	
	public function __isset($key){
		if(isset($this->attributes[$key]))
			return true;
		
		return false;
	}

}
