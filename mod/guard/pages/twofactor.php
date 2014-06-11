<?php
/**
 * Two factor pages
 */
namespace minds\plugin\guard\pages;

use minds\core;
use minds\interfaces;
use minds\entities;
use minds\plugin\guard\lib;

class twofactor extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		\elgg_set_context('settings');
		
		$twofactor = new lib\twofactor();
		
		$user = \elgg_get_logged_in_user_entity();
		if(!$user->twofactor_secret){
			$user->twofactor_secret = $twofactor->createSecret();
			$user->save();
		}
		$secret = $user->twofactor_secret;
		
		$AccountSid = "AC8d9ebda852cd20a7fa464f27ac89809d";
		$AuthToken = "5a75fc7e32f40158c35fd86cc85697ce";
		 
		try{
			$client = new \Services_Twilio($AccountSid, $AuthToken);
			 
			$message = $client->account->messages->create(
			    "+18563935384", // From this number
			    "+4407526916045", // To this number
			    "Test message!"
			);
		}catch(\Exception $e){
			echo $e->getMessage();
		}

		$qr = elgg_view('output/img', array('src'=>$twofactor->getQRCodeGoogleUrl($user->email, $secret)));
		
		$content = $qr;
		$content .= 'Please download an OTP app in order to use two factor authentication. We recommend <a target="_blank" href="https://support.google.com/accounts/answer/1066447?hl=en">Google Authenticator</a>';
		
		$body = \elgg_view_layout('content', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
