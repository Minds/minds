<?php
/**
 * Manages Minds sites (soon to be extended to other networks)
 */
class ElggDeckMinds extends ElggDeckNetwork{
	
	static $name;
	
	public function __construct($guid=null){
		parent::__construct($guid);		
		
		elgg_load_library('deck_river:minds_open_sdk');
	}

	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "minds_account";
		$this->attributes['network'] = 'minds';
	}

	private function consumerKeys(){
		/**
		 * For Minds Connect this would probably work a little bit differently. 
		 * Perhaps reverse OAuth or something? Each node knows the keys of the other
		 * nodes on the network, and the node just validates. 
		 */
	}

	/**
	 * Runs the authentication layer for the network
	 */
	public function authenticate(){
		/**
		 * An authentication plan needs to be added. It needs to be made so all nodes can talk, 
		 * but there should be a system for 'trusted' nodes. 
		 */
		return true;
	}

	public function revoke(){
		return true;
	}
	
	public function refresh(){
		return true;
	}
	
	private function mindsObj(){
		$options = array('node'=>$this->node);
		$mindsObj = new MindsOpen($options);
	}

	public function getData($method, array $params = array()){
		if($this->node == 'local'){
			switch($method){
				case 'trending':
					$options['object_guids'] = analytics_retrieve(array('limit'=>$options['limit']+3, 'offset'=>$options['offset']));
					$options['action_types'] = 'create';
					$options['offset'] = 0;
					$data = elgg_get_river($options);
				break;
				case 'timeline':
				default:
					$data = elgg_get_river(array('type'=>'timeline', 'owner_guid'=>$this->id));
			}
			foreach($data as $item){
				
				$items[] = $item->export();
				
			}
			$data = $items;
		} else {
			$mindsObj = $this->mindsObj();
			$data = $mindsObj->$method($params);
		}
		if(!is_array($data))
			return false;
		$first = $data[0];
		$last = end($data);
		return array('data'=>$data, 'pagination'=>array('previous'=>$first->guid, 'next'=>$last->guid));
	}
	
	/**
	 * Perform a post to the minds newsfeed
	 * 
	 * @param object $post_obj - the post object. 
	 */
	public function post($post_obj){
		if($this->node == 'local'){
			$wallpost = new WallPost();
			$wallpost->to_guid = $post_obj->to_guid ?: $post_obj->owner_guid;
			$wallpost->owner_guid = $post_obj->owner_guid;
			$wallpost->container_guid = $post_obj->container_guid;
			$wallpost->message = $post_obj->message;
			$wallpost->attachment = $post_obj->attachment;
			$wallpost->access_id = $post_obj->access_id;
			$wallpost->meta_title = $post_obj->meta_title;
			$wallpost->meta_description = $post_obj->meta_description;
			$wallpost->meta_icon = $post_obj->meta_icon;
			$wallpost->meta_url = $post_obj->meta_url;
			//add the message
			//var_dump($wallpost->owner); exit;
			//add_to_river('river/object/wall/create', 'create', $wallpost->owner_guid, $wallpost->save());
			$wallpost->save(); 

			$options = array(
				'cc' => array($wallpost->to_guid, "personal:$wallpost->to_guid"),
				'subject_guid' => $wallpost->owner_guid,
				'body' => $wallpost->message,
				'view' => 'river/object/wall/create',
				'object_guid' => $wallpost->guid, //needed until we do some changes to the thumbs and comments plugins
				'attachment_guid' => $post_obj->attachment,
				'access_id' => $wallpost->access_id,
				
				'meta_title' => $wallpost->meta_title,
				'meta_description' => $wallpost->meta_description,
				'meta_icon' => $wallpost->meta_icon,
				'meta_url' => $wallpost->meta_url,
				);
			if($wallpost->access_id == ACCESS_PRIVATE)
				$options['timeline_override'] = array($wallpost->to_guid); //only post to the to_guid timeline..
				
			$river = new ElggRiverItem($options);
			$river->save();
			
			return;
		} else {
			try{
				$mindsObj = $this->mindsObj();
				return $mindsObj->api('wall.post', 'POST', array('message' => $message));
			} catch (Exception $e){
				return $e->getMessage();
			}
		}
	}
	
	/**
	 * Performance action
	 */
	public function doAction($id, $method, $params){
		
	}
	
	/**
	 * Get sub accounts
	 * (twitter does not support this, return null)
	 */
	public function getSubAccounts(){
		return null;
	}
	
	/**
	 * Returns posts from the network
	 */
	public function getPosts($limit=12, $offset=""){
		//get some posts
	}
	
	/**
	 * Returns a specific post
	 */
	public function getPost($uid){
		//get a specific post
	}


}
