<?php
/**
 * OAUTH2 Refresh Token Entity
 */

namespace minds\plugin\oauth2\entities;

use minds\entities;
use minds\core\data;

class refreshToken extends entities\entity{
	
	protected $attributes = array(
		'type' => 'oauth2',
		'subtype' => 'refreshToken'
	);
	
	public function __construct($token = NULL){
		if($token)
			$this->load($token);
	}
	
	public function load($token){
		$lookup = new data\lookup('oauth2:token');
		$guid = $lookup->get($token);
		
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

		$lookup = new data\lookup('oauth2:token');
		$lookup->set($this->refresh_token, $guid);
	}
	
	public function delete(){
		parent::delete();
	}
	
	/*
	 * Return an array in OAuth2 format
	 */
	public function export(){
		return array(
			'access_token' => $this->refresh_token,
            'client_id'    => $this->client_id,
            'user_id'      => $this->owner_guid,
            'expires'      => $this->expires,
            'scope'        => $this->scope,
            'entity'       => $this,
        );
	}
}
