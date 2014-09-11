<?php
/**
 * Minds cluster entity
 */

namespace minds\entities;

class cluster extends entity{
	
	public function __construct($guid = NULL){
		
	}
	
	public function getNodes($limit=10000){
		
	}
	
	public function join($server_addr){
		//notify all members of this cluster
		echo 'joined with '.$server_addr;
	}
	
	public function leave(){
		
	}
	
}
