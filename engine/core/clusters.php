<?php
/**
 * Minds clusters controller
 */
namespace minds\core;


class clusters extends base{
	
	public $seeds = array('https://www.minds.com');
	public $ttl = 1800; //nodes live for half an hours, and then they have to reconfirm
		
	/**
	 * Init
	 */
	public function init(){
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\pages\\clusters";
		router::registerRoutes(array(
			"/api/v1/cluster" => "$path\\index",
			"/api/v1/authenticate" => "$path\\authenticate",
			"/api/v1/subscriptions" => "$path\\subscriptions"
		));
		
		\elgg_register_plugin_hook_handler('cron', 'halfhour', array($this, 'cron'));
		\elgg_register_plugin_hook_handler('action', 'login', array($this, 'login'));
		
		\elgg_register_event_handler('create', 'all', array($this, 'createHook'));
		
	}
	
	/**
	 * Called every minute so we can communicate with the rest of the cluster
	 */
	public function cron(){
	echo 1;	
		error_log('running cron to talk to cluster');
		
		//assert our position on the network
		foreach($this->seeds as $seed){
			
			try{
				
				$response = $this->call('GET', $seed, '/api/v1/cluster/master/join', array('uri'=>elgg_get_site_url()));
				var_dump($response);
				
				$db = new data\call('user_index_to_guid');
				$db->insert('clusters:master', $response, $this->ttl);
				
				
			}catch(\Exception $e){
				var_dump($e);		
				error_log('CLUSTER ERROR: '.$e);
				
			}
			
		}
		
	}
	
	/**
	 * Call
	 * 
	 * @description Vital for inter-node communications
	 */
	public function call($method, $address, $endpoint, array $data = array(), $secret = false){
		
		$ch = curl_init();

		switch (strtolower($method)) {
		    case 'post':
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
		    case 'delete':
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Override request type
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$endpoint .= '?' . http_build_query($data); //because post fields can not be sent with DELETE
				break;
		    case 'get':
		    default:
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				$endpoint .= '?' . http_build_query($data);
				break;
		}

		curl_setopt($ch, CURLOPT_URL, $address . $endpoint);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Minds Clusters v1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		if($secret){
			$signature = self::generateSignature($data, $secret);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	   			"X-MINDS-SIGNATURE: $signature"
	   		));
		}
		
		$result = curl_exec($ch);
		$errors = curl_error($ch);
		
		if($errors){
			throw new \Exception($errors);
		}
	
		return json_decode($result, true);	
		
	}
	
	public function login(){ 
		//check if the select node is this one or not. 
		$node_uri = \get_input('node');
		if($node_uri == elgg_get_site_url() || "https://$node_uri" == elgg_get_site_url() || "http://$node_uri" == elgg_get_site_url()){
			return true;
		}

		$username = get_input('username');
		$password = get_input('password');
		
		/**
		 * Confirm autorization from the other node
		 */
		try{
		 	$authenticate = $this->call('POST', $node_uri, 'api/v1/authenticate', array('username'=>$username, 'password'=>$password));
			if(!$authenticate){
				//try again forcing https...
				$node_uri = str_replace('http://', 'https://', $node_uri);
				 $authenticate = $this->call('POST', $node_uri, 'api/v1/authenticate', array('username'=>$username, 'password'=>$password));
			}
							
		}catch(\Exception $e){

			//$db = new data\call('user_index_to_guid');
			//$db->removeAttributes('clusters:master', array($node_uri));
			
			\register_error('Sorry, there was an issue communicating with the host');
			return false;
		}

		
		if(!$authenticate || $authenticate['error']){
			\register_error('Sorry, we could not succesfully authenticate you.');
			return false;
		}
		
		/**
		 * Now create a pseudo account and import information from the user
		 * 
		 * @todo maybe integrate OAuth2.0 at the point
		 */
		
		$user = new \minds\entities\user($authenticate['guid']);
		if(!$user->username){
			while(get_user_by_username($username)){
				$username .= rand(1000,9000);
			}
			$user->name = $authenticate['name'];
			$user->username = $authenticate['username'];
			$user->email = $authenticate['email'];
			$user->base_node = $node_uri;
			$user->salt = generate_random_cleartext_password(); // Note salt generated before password!
			$user->password = generate_user_password($user, generate_random_cleartext_password()); //random password because this isn't actually a user registered here
			$user->save();
		}
		
		//now lets just check that
		if($user->base_node && $user->base_node != $node_uri){
			\register_error('Sorry, we could not authorize your login. This user belongs to another base node: '. $user->base_node);
			return false;
		}
		
		$user->name = $authenticate['name'];
		$user->username = $authenticate['username'];
		$user->email = $authenticate['email'];
		$user->avatar_url = $authenticate['avatar_url'];
		$user->access_id = 2;
		
		// If this a trusted domain, check to see if it's an admin (and if so, grant admin)
		if ($this->isTrustedDomain($node_uri)) {
		    $user->admin = 'no';
		    if (isset($authenticate['admin']) && $authenticate['admin'])
			$user->admin = 'yes';
		}
		
		$user->enable();	
	
		if(!\login($user)){
			\register_error('Sorry, we could not authorize your login.');
			return false;
		}
		
		//now lets sync up this users newsfeed.
		$this->syncFeeds($user); 
		$this->syncCarousels($user);
		
		return false; //it has to be false for some odd reason.
	}

	/**
	 * Generate a signature
	 * 
	 * @param array $data
	 * @param string $secret
	 * @param string
	 */
	static public function generateSignature($data, $secret){
		//sort data array alphabetically by key
		ksort($data);
		
		//combine keys and values into one long string
		$dataString = "";
		foreach($data as $key => $value)
			$dataString .= $key.$value;

		//lowercase everything
		$dataString = strtolower($dataString);
		

		return hash_hmac("sha256",$dataString,$secret);
	}
	
	/**
	 * Generate a secret key
	 */
	static public function generateSecret($length = 128){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
  		return hash('sha256', substr( str_shuffle( $chars ), 0, $length ));
	}
	
	public function joinCluster($cluster, $server_uri){
		//notify everyone in the cluster
	}
	
	/**
	 * Get a list of domains trusted by this node.
	 */
	protected function getTrustedDomains() {
	   
	    // Probably want a better storage for this.
	    $trusted_domains = trim(elgg_get_plugin_setting('trusted_domains', 'minds_nodes'));
	    if (!$trusted_domains)
		$trusted_domains = 'minds.com';
	    
	    $trusted_domains = explode(' ', $trusted_domains);
	    
	    // Sanitise trusted domains (incase they were entered as urls)
	    foreach ($trusted_domains as $key => $url) {
		
		// Check for scheme
		if (preg_match('/https?:\/\//', $url))
		{
		    $details = parse_url($url);
		    $trusted_domains[$key] = $details['host'];
		}
		
	    }
	    
	    return $trusted_domains;
	}

	/**
	 * Return whether a given URL/node is trusted by this node.
	 * @param type $url
	 */
	public function isTrustedDomain($url) 
	{
	    // If there's no scheme, prepend https:// so parse_url will work.
	    if (!preg_match('/https?:\/\//', $url))
		$url = "https://$url";
	    
	    $details = parse_url($url);
	    $node = $details['host'];
	    
	    $trusted_domains = $this->getTrustedDomains();
	    
	    return in_array($node, $trusted_domains);
	}
	
	/**
	 * We may have to make this issue a cron job, as it could take a long time to post out
	 */	
	public function createHook($event, $object_type, $entity, $params = array()){
		
		if($entity->access_id != 2)
			return true;	
		
		switch($object_type){
			case 'activity':
				//get the list subscribers.
				$db = new data\call('friendsof');
				$subscribers = $db->getRow($entity->owner_guid, array('limit'=>10000));
				foreach($subscribers as $guid => $json){
					
					//old, localised timestamp...
					if(is_numeric($json)){
						continue;
					}
					
					$payload = json_decode($json, true);
					$secret = $payload['secret'];
					$host = $payload['host'];
					$export = $entity->export();
					foreach($export as $k => $v){
						if(is_array($v))
							$export[$k] = json_encode($export[$k]);
					}
					try{
						$val = $this->call('POST', $host, '/newsfeed/api/'.$guid, $export, $secret);
					}catch(\Exception $e){
						\register_error($e->getMessage());
					}
					
					
				}
				//var_dump($db->getRow($entity->owner_guid)); exit;
				break;
			case 'object':
			default:
				//currently not supported
		}
	}
	
	/**
	 * Sync activity feeds
	 * 
	 * @param object $user - the user object
	 */
	public function syncFeeds($user){
		
		//first, lets check that it is an external account
		if(!$user instanceof \minds\entities\user && !$user->base_node)
			return false;
	
		foreach(array('network', 'user') as $feed){	
			//gather the feeds (not all, just 30 of the latest)
			try{
				$data = $this->call("GET", $user->base_node, "newsfeed/$feed/$user->guid", array('limit'=>30, 'view'=>'json'));
			}catch(\Exception $e){}
			if($data){
				foreach($data['activity'][''] as $activity){
					$activity['ownerObj']['base_node'] = $user->base_node;
					$new = new \minds\entities\activity($activity);
					$new->external = true;
					$new->node = $user->base_node;
					$new->indexes = array(
						"activity:$feed:$user->guid"
					);
					$new->save();
				}
			}
		}
	}
	
	/**
	 * Sync carousels
	 */
	public function syncCarousels($user){
		//first, lets check that it is an external account
		if(!$user instanceof \minds\entities\user && !$user->base_node)
			return false;
		
		try{
			$data = $this->call("GET", $user->base_node, "$user->username/api/carousels", array('limit'=>30));
		}catch(\Exception $e){}
		
		if($data){
			foreach($data as $d){
				$item = new \minds\entities\carousel();
				$item->guid = $d['guid'];
				$item->title = $d['title'];
				$item->href = $d['href'];
				$item->ext_bg = $d['bg'];
				$item->owner_guid = $user->guid;
				$item->access_id = ACCESS_PUBLIC;
				$item->save();
			}
		}
	}
		
}
