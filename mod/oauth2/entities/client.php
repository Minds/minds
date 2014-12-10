<?php
/**
 * OAUTH2 Access Token Entity
 */

namespace minds\plugin\oauth2\entities;

use minds\entities;
use minds\core\data;

class client extends entities\object{
	
	protected $attributes = array(
		'type' => 'object',
		'subtype' => 'oauth2_client'
	);
	
	
	/*
	 * Return an array in OAuth2 format
	 */
	public function export(){
		return array(
			'client_secret' => $this->client_secret,
            'client_id'    => $this->client_id,
            'scopes'        => $this->scopes
        );
	}
}
