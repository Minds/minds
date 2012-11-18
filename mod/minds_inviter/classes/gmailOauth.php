<?php
	
	/* to register your app you must go to https://www.google.com/accounts/ManageDomains 
	 * and register your domain after that you will be given a key and a secret 
	 * the target path url should be the url where you have installed the handle.php file 
	 * you MUST include ?provider=gmail at the the end of the path for example
	 * http://contactpreview.djpate.com/oauth.php?provider=gmail
	 * */
	 
	 class gmailOauth extends oauthWrapper{
		
		protected $request_token_url = "https://www.google.com/accounts/OAuthGetRequestToken?scope=https://www.google.com/m8/feeds/";
		protected $access_token_url = "https://www.google.com/accounts/OAuthGetAccessToken";
		protected $provider = "gmail";
		
		public function __construct(){
			global $gmail_consumer_key,$gmail_consumer_secret;
			$this->consumer_key = $gmail_consumer_key;
			$this->consumer_secret = $gmail_consumer_secret;
			parent::__construct();
		}
		
		public function getLoginUrl(){
			$this->request_token_url .= "&oauth_callback=".$this->callback_url;
			$this->getRequestToken();
			return "https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=".$_SESSION['oauth'][$this->classname]['request']['oauth_token'];
		}
		
		public function getContacts(){
			$return_array = array();
			
			$this->oauth->setToken($_SESSION['oauth']['gmailOauth']['access']['oauth_token'],$_SESSION['oauth']['gmailOauth']['access']['oauth_token_secret']);
	        $this->oauth->fetch("https://www.google.com/m8/feeds/contacts/default/full?max-results=100000&alt=json",null,OAUTH_HTTP_METHOD_GET); // 100 000 should be enough :p
	        
	        $array = json_decode($this->oauth->getLastResponse(),true);
	        
			foreach($array['feed']['entry'] as $contact){
                
				$name = $contact['title']['$t'];
			
                $email = $contact['gd$email'][0]['address'];
				
				if($name==""){
					$name = $email;
				}
                
				array_push($return_array,array("name"=>$name,"email"=>$email));
			}
	        
	        return $return_array;
	        
		}
		
	}
?>
