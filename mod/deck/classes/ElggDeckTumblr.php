<?php
/**
 * Manages tumblr accounts and network calls
 */
class ElggDeckTumblr extends ElggDeckNetwork{

	static $name;

	public function __construct($guid=null){
		parent::__construct($guid);
		$this->init();
	}

	public function __wakeup(){
		$this->init();
	}

	public function init(){
		elgg_load_library('deck_river:tumblr_api');
	}

	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "tumblr_account";
		$this->attributes['network'] = 'tumblr';
	}

	private function consumerKeys(){
		return array(
			'key' => elgg_get_plugin_setting('tumblr_consumer_key', 'deck'),
			'secret' => elgg_get_plugin_setting('tumblr_consumer_secret', 'deck')
		);
	}

	/**
	 * Runs the authentication layer for the network
	 */
	public function authenticate(){
	
		
		$oauth_token = $_SESSION['request_token'];
		$oauth_token_secret = $_SESSION['request_token_secret'];
		$oauth_verifier = $_REQUEST['oauth_verifier']; // we cannot use get_input because there is #_=_ in verifier
		$error = false;

		// check if user has too many accounts
		/*if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
			$error[] = elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name));
		}*/

		// get token
		$keys = $this->consumerKeys();

		try{
				//var_dump($oauth_token);
			if($oauth_token){
				$client = new TumblrOAuth($keys['key'], $keys['secret'], $oauth_token, $oauth_token_secret);
				$token = $client->getAccessToken($oauth_verifier);
				unset($_SESSION['request_token']);
				unset($_SESSION['request_token_secret']);
			} else {
				$client = new TumblrOAuth($keys['key'], $keys['secret']);
				$request_token = $client->getRequestToken(elgg_get_site_url() . 'authorize/tumblr/');
				$_SESSION['request_token'] = $request_token['oauth_token'];
				$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];
				forward($client->getAuthorizeURL($request_token['oauth_token']));
			}

			//make sure don't register twice this twitter account for this user.
			//if (deck_river_get_networks_account('twitter_account', elgg_get_logged_in_user_guid(), $token->user_id)) {
				//$error[] = elgg_echo('deck_river:network:authorize:already_done');
			//}

		}catch(Exception $e){
			$error[] = $e->getMessage();
		}

		if (!$error && $token) {
			
			// get user info
			$userInfo = $client->get('user/info');

 			$this->access_id = 0;
			$this->owner_guid = elgg_get_logged_in_user_guid();
			$this->screen_name = $userInfo->response->user->name;
			$this->oauth_token = $token['oauth_token'];
			$this->oauth_token_secret = $token['oauth_token_secret'];
			$this->time_created = time();

			if ($this->save()) {
				// trigger authorization hook
				elgg_trigger_event('authorize', 'deck_river:tumblr', $this);

				$account_output = array(
					'network' => 'tumblr',
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

			echo elgg_echo('deck_river:network:authorize:error');
			echo '<script type="text/javascript">$(document).ready(function() {window.opener.authorizeError = '. json_encode($error) .';window.openerelgg.deck_river.network_authorize(false);});</script>';
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
				system_message(elgg_echo('deck_river:tumblr:revoke:success'));
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
		$tubmlrObj = $this->tubmlrObj();
		$creds = $tubmlrObj->get('/account/verify_credentials.json');
		$this->oauth_token = $creds->oauth_token;
		$this->oauth_token_secret = $creds->oauth_token_secret;
		$this->save();
	}

	public function tubmlrObj(){
		$keys = $this->consumerKeys();
		return $tubmlrObj = new TumblrOAuth($keys['key'],$keys['secret'], $this->oauth_token, $this->oauth_token_secret);
	}

	public function getData($method, array $params = array()){
		$tubmlrObj = $this->tubmlrObj();
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

		if($method == 'default')
			$method = 'user/dashboard';

		//@todo check $method to see if it is allowed!
		$method = str_replace('{base-hostname}', $this->screen_name . '.tumblr.com', $method);

		try{
			$result = $tubmlrObj->get($method, $params);
//			var_dump($result);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if($result->meta->status == 200){
			//$results = $result->__get('response');
			//for pagination, get the timestamp of the last tweet
			//$first = $results[0];
			//$last = end($results);
			return array('data'=>$result->response, 'pagination'=> array('previous'=>$first['id']+1, 'next'=>$last['id']-1));
		}
	}

	public function post($message){
		try{
			$tubmlrObj = $this->tubmlrObj();
			$this->verifyCredentials();
			return $tubmlrObj->post('blog/'. $this->screen_name . '.tumblr.com/post', array('body' => $message, 'type' => 'text'));
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
		$tubmlrObj = $this->tubmlrObj();

		$defaults = array(
			'count' => 30,
		);

		$params = array_merge($defaults, $params);

		try{
			$result = $tubmlrObj->get($method, $params);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		if($result->meta->status == 200){
			//$results = $result->__get('response');
			//for pagination, get the timestamp of the last tweet
			//$first = $results[0];
			//$last = end($results);
			return array('data'=>$result->response);
		}
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
