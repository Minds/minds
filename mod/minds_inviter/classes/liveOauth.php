<?php
	/* to get your consumer key & consumer secret you must go to https://manage.dev.live.com 
	 * Select Web aplication 
	 * Once you have the keys you still have to finish the process to publish your app
	 * that mean verify your domain & add essentials & text and logos info on the api reg site 
	 * then dont forget to click on publish application in the application status page */
	class liveOauth extends oauthWrapper{
		
		protected $request_token_url = "n/a";
		protected $access_token_url = "https://consent.live.com/AccessToken.aspx";
		protected $provider = "live";
		
		public function __construct(){
			global $live_consumer_key,$live_consumer_secret;
			$this->consumer_key = $live_consumer_key;
			$this->consumer_secret = $live_consumer_secret;
			parent::__construct();
		}
		
		public function getLoginUrl(){
			return "https://consent.live.com/Connect.aspx?wrap_client_id=".$this->consumer_key."&wrap_callback=".$this->callback_url."&wrap_scope=WL_Contacts.View";
		}
		
		public function handleCallback($code){
			/* we cant use the default function cause of the fake oauth they are using */
			
			$post = array("wrap_client_id"=>$this->consumer_key,
							"wrap_client_secret"=>$this->consumer_secret,
							"wrap_verification_code"=>$code,
							"wrap_callback"=>$this->callback_url,
							"idtype"=>"CID");
			
			$curl = curl_init($this->access_token_url);
			curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            parse_str(curl_exec($curl),$retour);
           
            $_SESSION['oauth'][$this->classname]['access_token'] = $retour['wrap_access_token'];
            $_SESSION['oauth'][$this->classname]['guid'] = $retour['uid'];
            
            return true;
            
		}
		
		public function getContacts(){
			
			$return_array = array();
			
			$headers = array(	"Accept: application/json",
								"Content-Type: application/json",
								"Authorization: WRAP access_token=".$_SESSION['oauth']['liveOauth']['access_token']);
			
			$url = "http://apis.live.net/V4.1/cid-".$_SESSION['oauth']['liveOauth']['guid'].'/Contacts/AllContacts?$type=portable';

			$curl = curl_init($url);
			
			curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
			$retour = json_decode(curl_exec($curl),true);
			
			foreach($retour['entries'] as $contact){
                $email = $contact['emails']['0']['value'];
                $name = $email; 
                if($email!=""){
					array_push($return_array,array("name"=>$name,"email"=>$email));
				}
            }
            
            return $return_array;
		}
		
	}
?>
