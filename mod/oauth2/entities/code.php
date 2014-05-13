<?php
/**
 * OAUTH2 code entity
 */

namespace minds\plugin\oauth2\entities;

use minds\entities;
use minds\core\data;

class code extends entities\entity{
	
	protected $attributes = array(
		'type' => 'oauth2',
		'subtype' => 'code'
	);
	
	public function __construct($code = NULL){
		if($code)
			$this->load($code);
	}
	
	public function load($code){
		$lookup = new data\lookup('oauth2:code');
		$guid = $lookup->get($code);
		
		if(!isset($guid[0]))
			throw new \Exception('Lookup failed');
		
		$db = new data\call('entities');
		$data = $db->getRow($guid[0], array('limit'=>200));
		
		foreach($data as $k => $v){
			$this->$k = $v;
		}
	}
	
	public function save(){
		$guid = parent::save();

		$lookup = new data\lookup('oauth2:code');
		$lookup->set($this->authorization_code, $guid);
	}
	
	public function delete(){
		//parent::delete();
		
		$lookup = new data\lookup('oauth2:code');
		$lookup->remove($this->authorization_code);
	}
	
	/*
	 * Return an array in OAuth2 format
	 */
	public function export(){
		return array(
			'authorization_code' => $this->authorization_code,
			'client_id'          => $this->client_id,
            'user_id'            => $this->owner_guid,
            'redirect_uri'       => $this->redirect_uri,
            'expires'            => $this->expires,
            'scope'              => $this->scope,
            'entity'             => $this,
        );
	}
}
