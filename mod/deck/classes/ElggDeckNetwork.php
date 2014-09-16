<?php
/**
 * Extended (abstract) class of ElggObject to make sure each network has the minimum functions
 * 
 */
abstract class ElggDeckNetwork extends ElggObject {

	public function __construct($guid=null){
		parent::__construct($guid);
	}

	/**
	 * Set the super subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['super_subtype'] = "deck_account";

	}

	/**
	 * Runs the authentication layer for the network
	 */
	abstract public function authenticate();
	
	/**
	 * Revokes a network, initiated by running delete(); function
	 */
	abstract public function revoke();
	
	/**
	 * Refresh authorisation (should be done every 60 days)
	 */
	abstract public function refresh();
	
	/**
	 * Post a status to the network
	 */
	abstract public function post($message);
	
	/**
	 * Perform an action
	 */
	 
	abstract public function doAction($id, $method, $params);
	
	/**
	 * Return a sub account (aka. pages or associated accounts)
	 */
	abstract public function getSubAccounts();
	
	/**
	 * Returns posts from the network
	 */
	abstract public function getPosts($limit=12, $offset="");
	
	/**
	 * Returns a specific post
	 */
	abstract public function getPost($uid);

}
