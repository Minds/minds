<?php
/**
 * Minds main page controller
 */
namespace minds\plugin\minder\pages;

use Minds\Core;
use minds\interfaces;

class main extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$body = \elgg_view_layout('one_column', array(
			'content'=>'testing'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		$action = $_POST['action'];
		$guid = $_POST['guid'];
		
		switch($action){
			case 'up':
				//check against this users queue to see if the user already voted them up, if so, its a match
				
				//remove from this users queue
				
				//add to this users up vote
				
				//add to the other users voted up list
				
				//if not yet a match, add to the queue of the other user
				break;
			case 'down':
				//remove from this users queue
				
				//add to the user down list
				
				//add to the other users down voted list
				
				
				break;
		}
	}
	
	public function put($pages){}
	public function delete($pages){}
	
}
