<?php
/**
 * Manages facebook accounts and network calls
 */
class ElggDeckFacebook extends ElggDeckNetwork{

	static $name;

	public function __construct($guid = NULL){
		parent::__construct($guid);
	
		//conflicts with minds_social plugin	
		//elgg_load_library('deck_river:facebook_sdk');
		
		$this->name = $this->name;
		$this->column_colour = 'blue';
	}
	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "facebook_account";
		$this->attributes['network'] = "facebook";
	}
	
	function fbObj($authenticated = false){
		
		$facebook = new Facebook(array(
					'appId'  => elgg_get_plugin_setting('facebook_app_id', 'deck'),
					'secret' => elgg_get_plugin_setting('facebook_app_secret', 'deck')
					//'cookie' => true
				));
		
		if($authenticated){
			$facebook->setAccessToken($this->oauth_token);
		}	
		
		return $facebook;
	}

	/**
	 * Runs the authentication layer for the network
	 */
	public function authenticate(){
		
		$facebook = $this->fbObj(false);
		
		$code = get_input('code', false);
		$error = false;
	
		if (!$code) {
			$loginUrl = $facebook->getLoginUrl(array(
				'redirect_uri' => (elgg_get_site_url() . 'authorize/facebook/'.$this->guid),
				'scope' => $this->getScopes(),
			));
			forward($loginUrl);
		} else {
			// check if user has too many accounts
			$max_accounts = (int) elgg_get_plugin_setting('max_accounts', 'elgg-deck_river');

			if ($max_accounts > 0 && deck_river_count_networks_account('all') >= (int) $max_accounts) {
				$error[] = elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name));
			}
		
			//we want a 60 day token
			$facebook->setExtendedAccessToken();	
			$token = $facebook->getAccessToken();
		
			$fbUserProfile = $facebook->api('/me'); // Récupere l'utilisateur
			
			// make sure don't register twice this facebook account for this user.
			if (deck_river_get_networks_account('facebook_account', elgg_get_logged_in_user_guid(), $fbUserProfile['id']) && !$this->guid) {
				$error[] = elgg_echo('deck_river:network:authorize:already_done');
			}
		
			if (!$error && $token) {
		
				$this->access_id = 0;
				$this->user_id = $fbUserProfile['id'];
				$this->name = $fbUserProfile['name'];
				$this->username = $fbUserProfile['username'];
				$this->oauth_token = $token;
				$this->time_created = time(); 
		
				echo elgg_view('page/elements/head');
				echo elgg_view('page/elements/foot');
		
				if ($fb_guid = $this->save()) {
					// trigger authorization hook
					elgg_trigger_event('authorize', 'deck_river:facebook', $this);
						
					// add head and foot for js script
					echo elgg_view('page/elements/head');
					echo elgg_view('page/elements/foot');
		
					$account_output = json_encode(array(
						'network' => 'facebook',
						'network_box' => elgg_view_entity($this, array(
												'view_type' => 'in_network_box',
											)),
						'full' => '<li id="elgg-object-' . $fb_guid . '" class="elgg-item">' . elgg_view_entity($this) . '</li>',
						//'code' => "elgg.deck_river.getFBGroups('{$facebook_account->user_id}', '{$token}', '{$fb_guid}');"
					));
					echo '<script type="text/javascript">$(self.window.opener.document).ready(function() {console.log(self.window.opener.elgg); self.window.opener.elgg.deck_river.network_authorize(' . $account_output . '); window.close();});</script>';
		
				} else {
					$error[] = elgg_echo('deck_river:network:authorize:error');
				}
		
			}
		
			if ($error) {
				// add head and foot for js script
				echo elgg_view('page/elements/head');
				echo elgg_view('page/elements/foot');
		
				echo elgg_echo('deck_river:network:authorize:error');
				echo '<script type="text/javascript">$(self.window.opener.document).ready(function() {self.window.opener.authorizeError = '. json_encode($error) .';self.window.opener.elgg.deck_river.network_authorize(false); window.close();});</script>';
			}
		}
	
		
	}

	/**
	 * An access token needs to be refreshed, in facebooks case once every 60 days. 
	 */
	public function refresh(){
		//just quickly forward the user to facebook..
		return $this->authenticate();
	}
	
	public function revoke(){

		if (elgg_instanceof($this->getOwnerEntity(), 'user')) {
	
			$user_deck_river_accounts_in_wire = json_decode($this->getOwnerEntity()->getPrivateSetting('user_deck_river_accounts_in_wire'), true);
		
			$user_deck_river_accounts_in_wire['position'] = array_diff($user_deck_river_accounts_in_wire['position'], array($entity->getGUID()));
			$user_deck_river_accounts_in_wire['pinned'] = array_diff($user_deck_river_accounts_in_wire['pinned'], array($entity->getGUID()));
			$this->getOwnerEntity()->setPrivateSetting('user_deck_river_accounts_in_wire', json_encode($user_deck_river_accounts_in_wire));
	
			if (get_readable_access_level($entity->access_id) == 'shared_network_acl') {
				delete_access_collection($entity->access_id);
			}
	
			// remove account
			if($this->delete()){
				system_message(elgg_echo('deck_river:facebook:revoke:success'));
				return true;
			}
		}

		register_error(elgg_echo('deck_river:network:revoke:error'));
		return false;
	}
	
	public function getScopes(){
		return 'read_friendlists,
			read_insights,
			read_mailbox,
			read_requests,
			read_stream,
			share_item,
			export_stream,
			status_update,
			video_upload,
			photo_upload,
			create_note,
			create_event,
			manage_friendlists,
			manage_notifications,
			manage_pages,
			publish_actions,
			publish_stream,
			user_about_me,
			user_activities,
			user_events,
			user_friends,
			user_groups,
			user_likes,
			user_location,
			user_relationships,
			user_subscriptions,
			user_website,
			friends_notes,
			friends_status,
			friends_groups,
			friends_likes,
			friends_photos,
			friends_relationships,
			friends_activities,
			friends_events,
			friends_videos';
	}
	
	/**
	 * Get data from facebook
	 */
	public function getData($method, array $params = array()){

		$facebook = $this->fbObj('/me/home');
		$facebook->setAccessToken($this->oauth_token);
	
		try{	
			$endpoint = '';
			switch($method){
				case 'default':
				case 'home':
					$query = '';
					if($next = $params['pagination']['next']){
						$next = html_entity_decode($next);
						$next = parse_url($next);
						$params = $next['query'];
						//$query = http_build_query($params);
						$query = $params;
					}
					$results = $facebook->api('me/home?'. $query);
					break;
				case 'accounts':
					$results = $facebook->api('me/accounts');
					break;
				case 'page':
					$page_uid = $params['page_uid'];
					$results = $facebook->api($page_uid.'/feed');
					break;
				case 'feed':
					$results = $facebook->api('me/feed');
					break;
				case 'statuses':
					$results = $facebook->api('me/statuses');
					break;
				case strstr($method, 'page/'):
					$results = false;
					$page_id = str_replace('page/', '', $method);
					$results = $facebook->api($page_id . '/feed');
					break;
				default:
					$results = array();
			}
		} catch(Exception $e){
			$result = $e->getResult();
			if($result['error']['code'] == 190){
				//$this->authenticate();
				return false;
			}
		}
		
		//return in data and pagination
		return array('data'=>$results['data'], 'pagination'=>$results['paging']);
	}

	/**
	 * Get pages
	 */
	public function getPages(){
		$accounts = $this->getData('accounts');
		$accounts = $accounts['data'];
		$pages = array();
		foreach($accounts as $account){
			$page = new ElggDeckFacebookPage();
			$page->sub_page = true;
			$page->name = $account['name'];
			$page->id = $account['id'];
			$page->parent_guid = $this->guid;
			$pages[$account['id']] = $page;
		}
		return $pages;
	}
	
	/**
	 * Post
	 */
	public function post($message){
		//var_dump($this->fbObj()->getAccessToken());
				
		try{
			return $this->fbObj()->api('me/feed', 'POST', array('message'=>$message));	
		}	catch(Exception $e){
			$this->authenticate();
			var_dump($e->getMessage()); exit;
			return $e->getMessage();
		}
	}
	
	/**
	 * Performance action
	 */
	public function doAction($id, $method, $params){
		try{
			switch($method){
				case 'like':
					if($this->fbObj()->api("$id/likes", 'POST')){
						system_message('deck_river:facebook:liked');
						return true;
					} else {
						register_error('failed');
					}
					break;
				case 'unlike':
					if($this->fbObj()->api("$id/likes", 'DELETE')){
						system_message('deck_river:facebook:liked');
						return true;
					} else {
						regsiter_error('failed');
					}
				break;
				case 'comment':
					return $this->fbObj()->api("$id/comments", 'POST', array('message'=>$params['message']));
				case 'share':
					break;
			}
		} catch(Exception $e){
			var_dump($e);
			echo $e->getMessage();
		}
		return false;
	}

	/**
	 * Get sub accounts
	 * AKA Pages
	 */
	public function getSubAccounts(){
		return $this->getPages();
	}
	
	public function getSubAccount($id){
		$accounts = $this->getSubAccounts();
		return $accounts[$id];
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
