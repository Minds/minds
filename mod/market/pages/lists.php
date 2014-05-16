<?php
/**
 * Market lists
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\market;
use minds\entities;

class lists extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$db = new core\data\call('entities_by_time');
		$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 12;
		$offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : "";
		
		$lookup = new core\data\lookup();
		
		$index = new core\data\indexes();
		var_dump($lookup->get('mark@minds.com'), $index->get('object:blog'));exit;
		
		switch($pages[0]){
			case 'owner':
				$owner = new entities\user($pages[0]);
				if(!$owner){
					echo "The user could not be found \n";
					return false;
				}
				$guids = $db->getRow("object:market:user:$owner->guid", array('limit'=>$limit, 'offset'=>$offset));
				var_dump($guids); exit;
				$content = 'This is the owner';
			case 'category':
				//join up the slugs to create the category filter
				break;
			case 'all':
			default:
				$guids = $db->getRow("object:market", array('limit'=>$limit, 'offset'=>$offset));
				$content = \elgg_list_entities(array('guids'=>$guids));
		}
		
		$body = \elgg_view_layout('one_column', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
