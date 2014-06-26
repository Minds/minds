<?php

namespace minds\plugin\minder\models;

class channel extends \ElggUser{
	
	public function __construct($guid = NULL){
		parent::__construct($guid);
	}
	
	/**
	 * The list of mutual upvotes a user has made
	 */
	public function mutuals(){
		
	}
	
	/**
	 * The list of up votes the user has made
	 */
	public function ups(){
		
	}
	
	/**
	 * The list of down votes the user has made
	 */
	public function downs(){
		
	}
	
	
	/**
	 * The list of up votes the user has received
	 */
	public function uped(){
		
	}
	
	/**
	 * The list of down votes the user has received. 
	 */
	public function downed(){
		
	}
	
}
