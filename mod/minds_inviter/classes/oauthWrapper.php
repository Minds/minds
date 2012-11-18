<?php

abstract class oauthWrapper {
		
	protected $consumer_key;
	protected $consumer_secret;
	protected $callback_url;
	protected $request_token_url;
	protected $access_token_url;
	protected $sig_method = OAUTH_SIG_METHOD_HMACSHA1;
	protected $auth_type = OAUTH_AUTH_TYPE_AUTHORIZATION;
	
	protected $oauth;
	protected $classname;
	
	public function __construct(){
		
		global $domain,$contactinvite_folder;
	
		$this->callback_url = $domain."/".$contactinvite_folder."/handle.php?provider=".$this->provider;
		
		$this->classname = get_class($this);

		$this->checkRequirements();

		$this->oauth = new Oauth($this->consumer_key,$this->consumer_secret,$this->sig_method,$this->auth_type);
		
	}
	
	public function getRequestToken(){
		
		try {
			
			$request_token_info = $this->oauth->getRequestToken($this->request_token_url);
			foreach($request_token_info as $id => $val){
				$_SESSION['oauth'][$this->classname]['request'][$id] = $val;
			}
		
		} catch (OAuthException $E){
			echo "Error : ".$E->getMessage();
			exit;
		}
	 
		
	}
	
	private function checkRequirements(){
		
		if(!class_exists("OAuth",false)){
			throw new Exception("You must install php5-oauth to use this class");
		}
		
		if(!function_exists("curl_init")){
			throw new Exception("You must install php5-curl to use this class");
		}
		
		if(is_null($this->consumer_key)||is_null($this->consumer_secret)){
			throw new Exception("Please specify both the consumer_key and the secret key in you class definition");
		}
		
		if(is_null($this->provider)){
			throw new Exception("Please specify a provider in your class");
		}
		
		if(is_null($this->request_token_url)){
			throw new Exception("Please specifiy a request token url");
		}
		
		if(is_null($this->access_token_url)){
			throw new Exception("Please specifiy a access token url");
		}
		
		if(is_null($this->callback_url)){
			throw new Exception("Please specifiy a callback url");
		}
		
	}
	
	public function handleCallback($token){
		
		try {
			
			$this->oauth->setToken($token,$_SESSION['oauth'][$this->classname]['request']['oauth_token_secret']);
			
			$access_token_info = $this->oauth->getAccessToken($this->access_token_url);
		
			if(!empty($access_token_info)){
				foreach($access_token_info as $id => $val){
					$_SESSION['oauth'][$this->classname]['access'][$id] = $val;
				}
			} else {
				die("Failed fetching access token, response was: " . $this->oauth->getLastResponse());
			}
			
			
			
			return true;
		
		} catch (OAuthException $E){
			print_r($_SESSION);
			print_r($E->debugInfo);
			print_r($E->lastResponse);
			print_r($this->oauth->getLastResponseInfo());
			die();
		}
		
		return false;
		
	}
		
}

?>
