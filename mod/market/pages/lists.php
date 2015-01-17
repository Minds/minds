<?php
/**
 * Market lists
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\market;
use minds\entities;

class lists extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$db = new core\Data\Call('entities_by_time');
		$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 12;
		$offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : "";
		
		$lookup = new core\Data\lookup();
		
		$index = new core\data\indexes();
		
		switch($pages[0]){
			case 'owner':
				$owner = new entities\user($pages[1]);
				if(!$owner->username){
					echo "The user could not be found \n";
					return false;
				}
				$content = core\entities::view(array('subtype'=>'market', 'owner_guid'=>$owner->guid, 'limit'=>$limit, 'offset'=> $offset, 'full_view'=>false));
				break;
			case 'category':
				if(!isset($pages[1])){
					$content = '';
					break;
				}
				$guids = $db->getRow("object:market:category:".$pages[1], array('limit'=>$limit, 'offset'=>$offset));
				if($guids)
					$content = core\entities::view(array('guids'=>$guids, 'full_view'=>false));
				else 
					$content = '';
				break;
			case 'featured':
				$guids = $db->getRow("object:market:featured", array('limit'=>$limit, 'offset'=>$offset));
				if($guids)
					$content = core\entities::view(array('guids'=>$guids, 'full_view'=>false));
				else 
					$content = '';
				break;
			case 'all':
			default:
				$guids = $db->getRow("object:market", array('limit'=>$limit, 'offset'=>$offset));
				if($guids)
					$content = core\entities::view(array('guids'=>$guids, 'full_view'=>false));
				else 
					$content = '';
		}
		
		$body = \elgg_view_layout('one_sidebar', array(
			'content'=>$content,
			'header' => elgg_view('market/header'),
			'sidebar' => elgg_view('market/sidebar'),
			'sidebar_class' => 'elgg-sidebar-alt'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
