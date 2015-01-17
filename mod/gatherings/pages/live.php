<?php
/**
 * Live chat helpers
 */
namespace minds\plugin\gatherings\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\gatherings\entities;

class live extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	

	public function get($pages){

		
	}
	
	public function post($pages){
				
		switch($pages[0]){
			case "userlist":
				
				/**
				 * Filters a user list to return only subscriptions
				 */
				$guids = $_POST['guids'];
				$mutuals = array();
                
                if(!$guids){
                    return false;
                }
				
				//$friends = new core\Data\Call('friends');
				//$friends = $friends->getRow(elgg_get_logged_in_user_guid(), array('limit'=>10000));
				$friendsof = new core\Data\Call('friendsof');
				$friendsof = $friendsof->getRow(elgg_get_logged_in_user_guid(), array('limit'=>10000));
				
				foreach($guids as $guid){
					if(isset($friendsof[$guid]))
						$mutuals[] = $guid;
				}
				
				echo json_encode($mutuals);
			
				break;
		}
	}

	public function put($pages){}

	public function delete($pages){}
	
}
