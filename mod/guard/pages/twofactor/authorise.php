<?php
/**
 * Two factor pages
 */
namespace minds\plugin\guard\pages\twofactor;

use minds\core;
use minds\interfaces;
use minds\entities;
use minds\plugin\guard\lib;

class authorise extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$twofactor = new lib\twofactor();
		$user = \elgg_get_logged_in_user_entity();
		
		/**
		 * Send our twofactor code to the user
		 */
		try{
			$AccountSid = "AC8d9ebda852cd20a7fa464f27ac89809d";
			
			$AuthToken = "5a75fc7e32f40158c35fd86cc85697ce";
			$client = new \Services_Twilio($AccountSid, $AuthToken);
			 
			$message = $client->account->messages->create(array( 
				'To' => "+447526916045", 
				'From' => "+18563935384", 
				'Body' => $twofactor->getCode($secret),   
			));
		}catch(\Exception $e){
			echo $e->getMessage();
		}
		
		$content = 'Enter your code';
		$content .= \elgg_view_form('guard/twofactor/authorise', array('action'=>\elgg_get_site_url().'login/twofactor'));		
		$body = \elgg_view_layout('content', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
		$user = \elgg_get_logged_in_user_entity();
		$twofactor = new lib\twofactor();
		$secret = $user->twofactor_secret;
		
		$code = \get_input('code');
		if($twofactor->verifyCode($secret, $code, 1)){
			$content = 'Success! You are now setup for two-factor authentication';
			$_SESSION['authorised'] = true;
		} else {
			$content = 'Sorry, that didn\'t work';
			$_SESSION['authorised'] = false;
			$this->forward(REFERRER);
		}
		

		$body = \elgg_view_layout('content', array('content'=>$content));
		echo $this->render(array('body'=>$body));
			
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
