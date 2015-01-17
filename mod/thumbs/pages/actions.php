<?php
/**
 * Thumb actions
 */
namespace minds\plugin\thumbs\pages;

use Minds\Core;
use minds\interfaces;

class actions extends core\page implements interfaces\page{
	
	
	public function get($pages){}
	

	public function post($pages){
		
		$guid = $pages[0];
		$action = $pages[1];
		
		$entity = core\entities::build(new \minds\entities\entity($guid));
		if(!$entity)
			throw new \Exception("Entity $guid not found");
		
		if($entity instanceof \minds\entities\activity && $entity->entity_guid)
			$entity = core\entities::build(new \minds\entities\entity($entity->entity_guid));
		
		switch($action){
			case "up":
				
				$this->magicInsert('up', $entity);
			
				break;
			case "up-cancel":
				
				$this->magicCancel('up', $entity);
				
				break;
			case "down";
			
				$this->magicInsert('down', $entity);
			
				break;
			case "down-cancel":
				
				$this->magicCancel('down', $entity);
				
				break;
		}
		
	}
	

	public function put($pages){}

	public function delete($pages){}
	
	/**
	 * Magic insert function
	 */
	private function magicInsert($direction = 'up', $entity){
		
		$db = new core\data\call('entities');
		$indexes = new core\data\call('entities_by_time');
		
		//quick and easy, direct insert to entity
		$db->insert($entity->guid, array("thumbs:$direction:count" => $entity->{"thumbs:$direction:count"} + 1));
		
		$user_guids = json_decode($entity->{"thumbs:$direction:user_guids"}, true) ?: array();
		$user_guids[] = elgg_get_logged_in_user_guid();
		$db->insert($entity->guid, array("thumbs:$direction:user_guids" => json_encode($user_guids)));
				
		//now add to the entity list of thumbed up users
		$indexes->insert("thumbs:$direction:entity:$entity->guid", array(elgg_get_logged_in_user_guid() => time()));
				
		//now add to the users list of thumbed up content
		$indexes->insert("thumbs:$direction:user:".elgg_get_logged_in_user_guid(), array($entity->guid => time()));
		$indexes->insert("thumbs:$direction:user:".elgg_get_logged_in_user_guid() .":$entity->type", array($entity->guid => time()));
	}

	private function magicCancel($direction = 'up', $entity){
		
		$db = new core\data\call('entities');
		$indexes = new core\data\call('entities_by_time');
		
	
		$db->insert($entity->guid, array("thumbs:$direction:count" => $entity->{"thumbs:$direction:count"} - 1));
		
		
		$user_guids = json_decode($entity->{"thumbs:$direction:user_guids"}, true) ?: array();
		$user_guids = array_diff($user_guids, array(elgg_get_logged_in_user_guid()));
		$db->insert($entity->guid, array("thumbs:$direction:user_guids" => json_encode($user_guids)));
				
		//now remove from the entities list of thumbed up users
		$indexes->removeAttributes("thumbs:$direction:entity:$entity->guid", array(elgg_get_logged_in_user_guid()));
				
		//now remove from the users list of thumbs up content
		$indexes->removeAttributes("thumbs:$direction:user:" . elgg_get_logged_in_user_guid(), array($entity->guid));
		$indexes->removeAttributes("thumbs:$direction:user:" . elgg_get_logged_in_user_guid() .":$entity->type", array($entity->guid));
	}
	
}
