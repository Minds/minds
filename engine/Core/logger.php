<?php
/**
 * Logs actions to the activity log
 */
namespace Minds\Core;

use Minds\Core\data;
class logger extends base{
	
	public function init(){
		\elgg_register_event_handler('create', 'all', array($this,'log'));
		\elgg_register_event_handler('update', 'all', array($this,'log'));
		\elgg_register_event_handler('delete', 'all', array($this,'log'));
		
		router::registerRoutes(array('/admin/log'=>"\\minds\pages\logger"));
	}
	
	public function log($event, $object_type, $object) {
		$db = new Data\Call('log');
		$data = array(
			'event'=>$event,
			'entity' => $object->guid,
			'user'=>\elgg_get_logged_in_user_guid(),
			'ts'=> time()
		);
		$db->insert(0, $data);
	}
	
	public function get($limit = 20, $offset = ''){
		$db = new Data\Call('log');
		foreach($db->get() as $id => $row){
			var_dump($id, $row);
		}
	}
	
}
