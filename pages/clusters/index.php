<?php
/**
 * Minds cluster default controller
 */
namespace minds\pages\clusters;

use minds\core;
use minds\interfaces;
use minds\entities;

class index extends core\page implements interfaces\page{
	
	public function get($pages){
		
		if(isset($pages[0])){
			
			if(!isset($pages[1]))
				$pages[1] = 'info';
			
			switch($pages[1]){
				case "join":
					//join the cluster
					$cluster = new entities\cluster($pages[0]);
					$cluster->join($_REQUEST['uri']);
					break;
				case "leave":
					//leave the cluster
					break;
				case "poll":
					//poll the cluster to say "i'm still here"
					break;
				default:
				case "info":
					//get information on the nodes in the cluster, up 
			}
		} else {
			//provide a list of clusters in the node
		}
		
	}
	
	public function post($pages){

		if($_POST['time'] != ''){
			\register_error('sorry, wrong answer');
			return false;
		}
			
		\elgg_send_email($_POST['email'], array('mark@minds.com','bill@minds.com'), 'New Email from ' . $_POST['name'], $_POST['message']);
		
		\system_message('Success!');
		
		$this->get($pages);
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
