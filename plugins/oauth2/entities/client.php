<?php
/**
 * OAUTH2 Access Token Entity
 */

namespace minds\plugin\oauth2\entities;

use Minds\Entities;
use Minds\Core\data;

class client extends Entities\Object{
	
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
