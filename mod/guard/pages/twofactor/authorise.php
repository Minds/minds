<?php
/**
 * Two factor pages
 */
namespace minds\plugin\guard\pages\twofactor;

use Minds\Core;
use minds\interfaces;
use Minds\Entities;
use minds\plugin\guard\lib;

class authorise extends core\page implements Interfaces\page{

	public $csrf = false;
	
	/**
	 * Get requests
	 */
	public function get($pages){
		/**
		 * Send our twofactor code to the user
		 */
		$content .= \elgg_view_form('guard/twofactor/authorise', array('action'=>\elgg_get_site_url().'login/twofactor/'.$pages[0], 'class'=>'elgg-form elgg-form-login'));		
		$body = \elgg_view_layout('one_column', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
		$twofactor = new lib\twofactor();
		
		//get our one user twofactor token
		$lookup = new \Minds\Core\Data\lookup('twofactor');
		$return = $lookup->get($pages[0]);
		$lookup->remove($pages[0]);
		
		//we allow for 120 seconds (2 mins) after we send a code
		if($return['_guid'] && $return['ts'] > time()-120){
			$user = new \ElggUser($return['_guid']);
			$secret = $return['secret'];
		}else {
			//no user could be found.. maybe because the token expired?
			\register_error('Your token was invalid');
			$this->forward('login');
			return false;
		}
		
		if($twofactor->verifyCode($secret, \get_input('code'), 1)){
			
			global $TWOFACTOR_SUCCESS;
			$TWOFACTOR_SUCCESS = true;
			\login($user, true);
			
			$this->forward($user->getURL());
		
		} else {
			\register_error('Sorry, we could not verify your account');
			$this->forward('/login');
		}
		
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
