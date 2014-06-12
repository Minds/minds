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
		
		if($user->twofactor){
			$content = 'You are setup with twofactor authentication';
		} else {
			$content = 'Enter your mobile number';
			$content .= \elgg_view_form('guard/twofactor/setup', array('action'=>\elgg_get_site_url().'settings/twofactor/setup'));
		}
				
		$body = \elgg_view_layout('content', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
		$user = \elgg_get_logged_in_user_entity();
		$twofactor = new lib\twofactor();
		$secret = $user->twofactor_secret;
		
		switch($pages[0]){
			
			case 'setup':
				$user = \elgg_get_logged_in_user_entity();
				$user->telno = \get_input('tel');
				$user->save();
				
				$AccountSid = "AC8d9ebda852cd20a7fa464f27ac89809d";
				$AuthToken = "5a75fc7e32f40158c35fd86cc85697ce";
		
				try{
					$client = new \Services_Twilio($AccountSid, $AuthToken);
					 
					$message = $client->account->messages->create(array( 
						'To' => "+447526916045", 
						'From' => "+18563935384", 
						'Body' => $twofactor->getCode($secret),   
					));
				}catch(\Exception $e){
					echo $e->getMessage();
				}
				
				$content = 'We just sent you a text message. Please enter the code below';
				$content .= \elgg_view_form('guard/twofactor/check', array('action'=>\elgg_get_site_url().'settings/twofactor/check'));

				break;
		
			case 'check':
				
				$code = \get_input('code');
				if($twofactor->verifyCode($secret, $code, 1)){
					$content = 'Success! You are now setup for two-factor authentication';
					$user->twofactor = true;
				} else {
					$content = 'Something didn\'t go to plan.. Please try again.';
					$user->twofactor = false;
				}
				$user->save();
				break;
			default:
				return false;
			
		}

		$body = \elgg_view_layout('content', array('content'=>$content));
		echo $this->render(array('body'=>$body));
			
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
