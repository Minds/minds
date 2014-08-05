<?php
/**
 * Manages LinkedIn accounts and network calls
 */
class ElggDeckLinkedin extends ElggDeckNetwork{

	static $name;

	public function __construct($guid=null){
		parent::__construct($guid);
		elgg_load_library('deck_river:linkedin_api');
		
		$this->name = $this->name;
	}

	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'linkedin_account';
		$this->attributes['network'] = 'linkedin';
	}

	private function consumerKeys(){
		return array(	'key' => elgg_get_plugin_setting('linkedin_consumer_key', 'deck'),
						'secret' => elgg_get_plugin_setting('linkedin_consumer_secret', 'deck'));
	}

	/**
	 * Runs the authentication layer for the network
	 */
	public function authenticate(){

		$oauth_token = get_input('code', false);
		$error = false;

		// check if user has too many accounts
		/*if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
			$error[] = elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name));
		}*/

		// get token
		$keys = $this->consumerKeys();

		try{
			$linkedinObj = $this->linkedinObj();

			if($oauth_token){
				$token = $linkedinObj->getAccessToken($oauth_token);
			} else {
				$url = $linkedinObj->getLoginUrl($this->getScopes());
				forward($url);
			}

			//make sure don't register twice this twitter account for this user.
			//if (deck_river_get_networks_account('twitter_account', elgg_get_logged_in_user_guid(), $token->user_id)) {
				//$error[] = elgg_echo('deck_river:network:authorize:already_done');
			//}

		} catch(Exception $e) {
			$error[] = $e->getMessage();
		}

		if (!$error && $token) {

			// get avatar
			try{
				$userInfo = $linkedinObj->get('/people/~:(id,formatted-name,picture-url)');
			} catch(Exception $e){
				register_error($e->getMessage());
				return false;
			}

			// Set default picture if user don't have a picture-url
			if (!isset($userInfo['pictureUrl'])) $userInfo['pictureUrl'] = 'http://s.c.lnkd.licdn.com/scds/common/u/images/themes/katy/ghosts/person/ghost_person_30x30_v1.png';

			$this->access_id = 0;
			$this->owner_guid = elgg_get_logged_in_user_guid();
			$this->user_id = $userInfo['id'];
			$this->screen_name = $userInfo['formattedName'];
			$this->oauth_token = $token;
			$this->avatar = $userInfo['pictureUrl'];
			$this->time_created = time();

			if ($this->save()) {
				// trigger authorization hook
				elgg_trigger_event('authorize', 'deck_river:linkedin', $this);

				$account_output = array(
					'network' => 'linkedin',
					'network_box' => elgg_view_entity($this, array(
											'view_type' => 'in_network_box',
										)),
					'full' => '<li id="elgg-object-' . $this->getGUID() . '" class="elgg-item">' . elgg_view_entity($this) . '</li>'
				);

				// add head and foot for js script
				echo elgg_view('page/elements/head');
				echo elgg_view('page/elements/foot');

				echo '<script type="text/javascript">$(document).ready(function() {window.opener.elgg.deck_river.network_authorize(' . json_encode($account_output) . ');window.close()});</script>';
			} else {
				$error[] = elgg_echo('deck_river:network:authorize:error');
			}
		}

		if ($error) {
			// add head and foot for js script
			echo elgg_view('page/elements/head');
			echo elgg_view('page/elements/foot');

			echo elgg_echo('deck_river:network:authorize:error');
			echo '<script type="text/javascript">$(document).ready(function() {window.opener.authorizeError = '. json_encode($error) .';window.opener.elgg.deck_river.network_authorize(false); window.close();});</script>';
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
		$linkedinObj = $this->linkedinObj();
		$creds = $linkedinObj->get('/account/verify_credentials.json');
		$this->oauth_token = $creds->oauth_token;
		$this->oauth_token_secret = $creds->oauth_token_secret;
		$this->save();
	}

	public function linkedinObj(){
		$keys = $this->consumerKeys();
		return $linkedinObj = new LinkedIn(array(
			'api_key' => $keys['key'],
			'api_secret' => $keys['secret'],
			'callback_url' => elgg_get_site_url().'authorize/linkedin'
		));
	}

	public function getData($method, array $params = array()){
		$linkedinObj = $this->linkedinObj();
		$linkedinObj->setAccessToken($this->oauth_token);

		$defaults = array(
			'count' => 30,
		);
		$params = array_merge($defaults, $params);

		$method = $this->formatMethod($method);
		$params = array_merge($method['params'], $params); // add params

		try{
			$results = $linkedinObj->get($method['url'], $params);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if ($results){
			//for pagination, get the timestamp of the last post
			$first = $results['values'][0]['timestamp'];
			$last = end($results['values']);
			$last = $last['timestamp'];
			return array(
				'data' => $results,
				'pagination' => array(
					'previous' => $first+1,
					'next'=> $last-1
			));
		}
	}

	public function formatMethod($method) {
		switch ($method) {
			case 'memberUpdates':
				$return = array(
					'url' => 'people/~/network/updates',
					'params' => array('scope' => 'self')
				);
				break;
			case 'networkUpdates':
				$return = array(
					'url' => 'people/~/network/updates',
					'params' => array('type' => 'APPS&type=CMPY&type=CONN&type=JOBS&type=JGRP&type=PICT&type=PFOL&type=PRFX&type=RECU&type=PRFU&type=SHAR&type=VIRL')
				);
				break;
			case 'groupUpdates':
				$return = array(
					'url' => 'groups/' . $this->linkedin_group . '/posts:(creation-timestamp,title,summary,comments,creator:(first-name,last-name,picture-url,headline),likes,attachment:(image-url,content-domain,content-url,title,summary),relation-to-viewer)',
					'params' => array('count' => '30')
				);
				break;
			case 'companyUpdates':
				$return = array(
					'url' => 'companies/' . $this->linkedin_company . '/updates',
					'params' => array('type' => 'APPS&type=CMPY&type=CONN&type=JOBS&type=JGRP&type=PICT&type=PFOL&type=PRFX&type=RECU&type=PRFU&type=SHAR&type=VIRL')
				);
				break;
			default:
				return false;
		}

		return $return;
	}

	public function getScopes(){
		return array(
			LinkedIn::SCOPE_BASIC_PROFILE,
			LinkedIn::SCOPE_FULL_PROFILE,
			LinkedIn::SCOPE_EMAIL_ADDRESS,
			LinkedIn::SCOPE_NETWORK,
			LinkedIn::SCOPE_READ_WRITE_UPDATES,
			LinkedIn::SCOPE_READ_WRITE_GROUPS,
			LinkedIn::SCOPE_WRITE_MESSAGES
		);
	}

	public function post($message){
		try{
			$linkedinObj = $this->linkedinObj();
			$linkedinObj->setAccessToken($this->oauth_token);

			// get metadatas
			$url = get_input('link_url', false);
			$title = get_input('link_name');
			$desc = get_input('link_description');
			$picture = get_input('link_picture');
			$content = array();

			// format to linkedIn conditions
			$message = elgg_substr($message, 0, 700); // only 700 characters allowed
			if ($url) {
				$content['title'] = elgg_substr($title, 0, 200); // only 200 characters allowed
				$content['description'] = elgg_substr($desc, 0, 256); // only 256 characters allowed
				$content['submittedUrl'] = $url;
				$content['submittedImageUrl'] = $picture;
			}

			return $linkedinObj->post('people/~/shares', array(
				'comment' => $message,
				'content' => $content,
				'visibility' => array('code' => 'anyone' )
			));
		} catch (Exception $e){
			$error = json_decode($e->getMessage())->errors;
			register_error($error['message']);
			return $e->getMessage();
		}
	}

	/**
	 * Performance action
	 */
	public function doAction($id, $method, $params) {
		$payload = null;
		$comment = get_input('comment');
		if ($comment) {
			$payload = array(
				'comment' => $comment
			);
		}
		try{
			$linkedinObj = $this->linkedinObj();
			$linkedinObj->setAccessToken($this->oauth_token);

			return $linkedinObj->$method($params, $payload);
		} catch (Exception $e){
			$error = json_decode($e->getMessage())->errors;
			register_error($error['message']);
			return $e->getMessage();
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
