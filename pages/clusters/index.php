<?php
/**
 * Minds cluster default controller
 */
namespace minds\pages\clusters;

use Minds\Core;
use minds\interfaces;
use Minds\Entities;

class index extends core\page implements Interfaces\page{
	
	public function get($pages){
		
		if(isset($pages[0])){
			
			if(!isset($pages[1]))
				$pages[1] = 'info';
			
			switch($pages[1]){
				case "join":
					//error_log('CLUSTER JOIN REQUEST FROM: '. $_REQUEST['uri']);

					//join the cluster
					$cluster = new entities\cluster($pages[0]);
					$nodes = $cluster->join($_REQUEST['uri']);
					//var_dump($nodes);
					echo json_encode($nodes);
					break;
				case "leave":
					//leave the cluster
					break;
				case "poll":
					//poll the cluster to say "i'm still here"
					break;
				case "info":
				default:
					
					//get information on the nodes in the cluster, up 
			}
		} else {
			//provide a list of clusters in the node
		}
		
	}
	
	public function post($pages){}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
