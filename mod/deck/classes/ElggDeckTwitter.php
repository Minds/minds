<?php
/**
 * Manages twitter accounts and network calls
 */
class ElggDeckTwitter extends ElggDeckNetwork{
	
	static $name;
	
	public function __construct($guid=null){		
		parent::__construct($guid);		
		$this->init();
	}

	public function __wakeup(){
		$this->init();
	}

	public function init(){
		elgg_load_library('deck_river:twitter_async');

                $this->name = '@'.$this->screen_name;
		
		$this->column_colour = 'black';
	}

	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "twitter_account";
		$this->attributes['network'] = 'twitter';
		$tihs->attributes['column_colour'] = 'black';
	}

	private function consumerKeys(){
		return array(	'key' => elgg_get_plugin_setting('twitter_consumer_key', 'deck'),
						'secret' => elgg_get_plugin_setting('twitter_consumer_secret', 'deck'));
	}

	/**
	 * Runs the authentication layer for the network
	 */
	public function authenticate(){
			
		$oauth_token = get_input('oauth_token', false);
		$error = false;

		// check if user has too many accounts
                $max_accounts = (int) elgg_get_plugin_setting('max_accounts', 'elgg-deck_river');
                if ($max_accounts > 0 && deck_river_count_networks_account('all') >= (int) $max_accounts) {
	
                	$error[] = elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name));
                 }
	
		// get token
		$keys = $this->consumerKeys();
		
		try{
			$twitterObj = new EpiTwitter($keys['key'], $keys['secret']);
			if($oauth_token){
				$twitterObj->setToken($oauth_token);
				$token = $twitterObj->getAccessToken();
			} else {
				forward($twitterObj->getAuthenticateUrl());
			}
		
			//make sure don't register twice this twitter account for this user.
			//if (deck_river_get_networks_account('twitter_account', elgg_get_logged_in_user_guid(), $token->user_id)) {
				//$error[] = elgg_echo('deck_river:network:authorize:already_done');
			//}
			
		}catch(Exception $e){
			$error[] = $e->getMessage();
			var_dump($e); exit;
		}
	
		if (!$error && $token) {
	
			// get avatar
			try{
				$twitterObj = new EpiTwitter($keys['key'], $keys['secret'], $token->oauth_token, $token->oauth_token_secret);
				$userInfo = $twitterObj->get('/account/verify_credentials.json');
			} catch(Exception $e){
				register_error($e->getMessage());
			var_dump($e); exit;
				return false; 
			}
			//var_dump($token->user_id); exit;
			$this->access_id = 0;
			$this->owner_guid = elgg_get_logged_in_user_guid();
			$this->user_id = $token->user_id;
			$this->screen_name = $token->screen_name;
			$this->oauth_token = $token->oauth_token;
			$this->oauth_token_secret = $token->oauth_token_secret;
			$this->avatar = $userInfo->response['profile_image_url_https'];
			$this->time_created = time(); 
			
			if ($this->save()) {
				// trigger authorization hook
				elgg_trigger_event('authorize', 'deck_river:twitter', $twitter_account);
	
				$account_output = array(
					'network' => 'twitter',
					'network_box' => elgg_view_entity($this, array(
											'view_type' => 'in_network_box',
										)),
					'full' => '<li id="elgg-object-' . $this->getGUID() . '" class="elgg-item">' . elgg_view_entity($this) . '</li>'
				);
					
				// add head and foot for js script
				echo elgg_view('page/elements/head');
				echo elgg_view('page/elements/foot');
	
				echo '<script type="text/javascript">$(document).ready(function() {window.opener.elgg.deck_river.network_authorize(' . json_encode($account_output) . '); window.close();});</script>';
			} else {
				$error[] = elgg_echo('deck_river:network:authorize:error');
			}
		}
	
		if ($error) {
			// add head and foot for js script
			echo elgg_view('page/elements/head');
			echo elgg_view('page/elements/foot');
	
			//echo elgg_echo('deck_river:network:authorize:error');
			//var_dump('error',$error, $this); exit;
			echo '<script type="text/javascript">$(document).ready(function() {window.opener.authorizeError = '. json_encode($error) .';window.opener.elgg.deck_river.network_authorize(false);});</script>';
		}
	}

	public function revoke(){

		if (elgg_instanceof($this->getOwnerEntity(), 'user')) {
	
			$user_deck_river_accounts_in_wire = json_decode($this->getOwnerEntity()->getPrivateSetting('user_deck_river_accounts_in_wire'), true);
	
			// remove account from pinned accounts
			$user_deck_river_accounts_in_wire['position'] = array_diff($user_deck_river_accounts_in_wire['position'], array($entity->getGUID()));
			$user_deck_river_accounts_in_wire['pinned'] = array_diff($user_deck_river_accounts_in_wire['pinned'], array($entity->getGUID()));
			$this->getOwnerEntity()->setPrivateSetting('user_deck_river_accounts_in_wire', json_encode($user_deck_river_accounts_in_wire));
	
			if (get_readable_access_level($entity->access_id) == 'shared_network_acl') {
					delete_access_collection($entity->access_id);
			}
	
			// remove account
			if($this->delete()){
				system_message(elgg_echo('deck_river:twitter:revoke:success'));
				return true;
			}
		}
	
		register_error(elgg_echo('deck_river:network:revoke:error'));
		return false;
	}

	public function refresh(){
		return $this->authenticate();	
	}

	public function verifyCredentials(){
		$twitterObj = $this->twitterObj();
		$creds = $twitterObj->get('/account/verify_credentials.json');
		$this->oauth_token = $creds->oauth_token;
		$this->oauth_token_secret = $creds->oauth_token_secret;
		$this->save();
	}

	public function twitterObj(){
		$keys = $this->consumerKeys();
		return $twitterObj = new EpiTwitter($keys['key'],$keys['secret'], $this->oauth_token, $this->oauth_token_secret);
	}

	public function getData($method, array $params = array()){
		$twitterObj = $this->twitterObj();
		// Set options
		$defaults = array(
			'count' => 30,
		);
		$pagination = $params['pagination'];
		unset($params['pagination']);
		if($next =  $pagination['next']){
			$params['max_id'] = $next;
		}
		
		$params = array_merge($defaults, $params); 
		//@todo check $method to see if it is allowed!
		try{
			$result = $twitterObj->$method($params);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if($result->code == 200){
			$results = $result->__get('response'); 
			//for pagination, get the timestamp of the last tweet
			$first = $results[0];
			$last = end($results);
			return array('data'=>$results, 'pagination'=> array('previous'=>$first['id']+1, 'next'=>$last['id']-1));
		}
	}
	
	public function post($message){
		try{
			$twitterObj = $this->twitterObj();
			$this->verifyCredentials();
			return $twitterObj->post('/statuses/update.json', array('status' => $message));
		} catch (Exception $e){
			$error = json_decode($e->getMessage())->errors;
			register_error($error['message']);
			return $e->getMessage();
		}
		
	}
	
	/**
	 * Performance action
	 */
	public function doAction($id, $method, $params){
		
	}
	
	/**
	 * Get sub accounts
	 * (twitter does not support this, return null)
	 */
	public function getSubAccounts(){
		return null;
	}
	
	/**
	 * Returns posts from the network
	 */
	public function getPosts($limit=12, $offset=""){
		//get some posts
	}
	
	/**
	 * Returns a specific post
	 */
	public function getPost($uid){
		//get a specific post
	}


}
