<?php
	/* you need to setup an API key from yahoo by going to this address 
	 * 
	 * https://login.yahoo.com/config/login_verify2?.src=devnet&.done=http%3A%2F%2Fdeveloper.apps.yahoo.com%2Fdashboard%2FcreateKey.html
	 * 
	 * be sure to check "This app requires access to private user data."
	 * 
	 * and to select read access to Yahoo! Contacts */


	class yahooOauth extends oauthWrapper{
		
		protected $request_token_url = "https://api.login.yahoo.com/oauth/v2/get_request_token";
		protected $access_token_url = "https://api.login.yahoo.com/oauth/v2/get_token";
		protected $provider = "yahoo";
		protected $auth_type = OAUTH_AUTH_TYPE_URI;
		
		public function __construct(){
			global $yahoo_consumer_key,$yahoo_consumer_secret;
			$this->consumer_key = $yahoo_consumer_key;
			$this->consumer_secret = $yahoo_consumer_secret;
			parent::__construct();
		}
		
		public function getLoginUrl(){
			$this->request_token_url .= "?oauth_callback=".$this->callback_url;
			$this->getRequestToken();
			return $_SESSION['oauth'][$this->classname]['request']['xoauth_request_auth_url'];
		}
		
		public function getContacts(){
			
			$return_array = array();
			
			$this->oauth->setToken($_SESSION['oauth']['yahooOauth']['access']['oauth_token'],$_SESSION['oauth']['yahooOauth']['access']['oauth_token_secret']);
           
            $ret = $this->oauth->fetch("http://social.yahooapis.com/v1/user/".$_SESSION['oauth']['yahooOauth']['access']['xoauth_yahoo_guid']."/contacts?format=json");
            
            $array = json_decode($this->oauth->getLastResponse(),true);
            
            foreach($array['contacts']['contact'] as $contact){
                $email = $contact['fields'][0]['value'];
                $name = $contact['fields'][1]['value']['givenName']." ".$contact['fields'][1]['value']['familyName'];
                if($email != ""){
					array_push($return_array,array("name"=>$name,"email"=>$email));
				}
            }
            
            return $return_array;
		}
		
	}
?>
