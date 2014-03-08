<?php
/**
 * A php sdk for Babelroom
 * 
 * @package Babelroom
 * @author Mark Harding (mark@minds.com)
 */
 
namespace bblr;

class api{
	
	public $api_key = "65dc149155ab4e26d0b207eed9d3c710";
	public $api_server = "https://api.babelroom.com";
	
	public function __construct(){
		
	}
	
	/**
	 * Call the api server and return the results
	 * 
	 * @param string $verb - eg. Get, Post, Put, Delete
	 * @param string $endpoint - the endpoint for the service. ie. /v1/status
	 * @param array $data
	 * @param string $result
	 * 
	 * @return string
	 */
	public function call($verb, $endpoint, $data = array(), &$result = NULL) {
		 
	    $url = $this->api_server.$endpoint;
	    $rc = false;
	
	    if (!extension_loaded('curl'))
			return false;
	
	    if ( !(stripos(ini_get('disable_functions'), 'curl_init') !== FALSE) && ($ch = @curl_init($url)) !== false) {
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);                                                                     
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_HEADER, false); -- for later reference
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	        curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':');
	        if ($data) {
	            $data_string = json_encode($data);
	            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	                'Content-Type: application/json',
	                'Content-Length: ' . strlen($data_string)));
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			}
			
	        $tmp_result = curl_exec($ch);
	        if (!curl_errno($ch)) {
	            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	            if ($code>=200 and $code<=299) {
	                $result = json_decode($tmp_result);
				}else {
	        		elgg_log("BRAPI error response code $code, [$server_url]",'ERROR');
				}
			} else {
				elgg_log("BRAPI connect error [$server_url]",'ERROR');
			}
	        curl_close($ch);
		} else {
	        elgg_log("BRAPI curl initialization error [$server_url]",'ERROR');
		}
	
	  return $result;
	}

	/**
	 * Status check the api
	 */
	public function status(){
    	return $this->call('GET', '/api/v1/status', null);
	}
	
	public function createInvitation($babelroom_id, $user, $avatar_url, $is_host){
		$params = array(
			'return_token' => true,
			'name' => $user['first'],
			'user' => array(
		    	'name' => $user['first'],
		    	'last_name' => $user['last'],
		   	 	'email' => $user['email'],
		    	'origin_data' => 'ElggUser(Guid)',
		    	'origin_id' => $user['id'],
				/* other fields of interest ... (in user)
		            'phone' => _util_canon_phone($user->phone1),
		   	    	 enabled
		      	 	 language
		    	    banned
				*/	
				//'language' => $user['language'], -- no, server barfs @ 2.37
			),
			'avatar_url' => $avatar_url,
			'invitation' => array(
		    	'role' => (($is_host) ? 'Host': null),
		    ),
		);
		return $this->call('POST', '/api/v1/add_participant/i/'.$babelroom_id, $params, $result);
	}
	
	public function createConference($conference) {
		
		$params = array(
			'name' => $conference['name'],
			'introduction' => $conference['description'],
			'origin_data' => "Elgg/" . get_version(true) . "/owner(Guid)",
			'origin_id' => $conference['owner_guid'],
		);
		
		$result = $this->call('POST', '/api/v1/conferences', $params); 
		if (!$result || empty($result->data) || empty($result->data->id))
		    return false;
		
		return $result->data->id;
	}
	
}