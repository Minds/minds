<?php
/**
 * Minds main page controller
 */
namespace minds\pages\emails;

use Minds\Core;
use minds\interfaces;

class unsubscribe extends core\page implements interfaces\page{

	/**
	 * Get requests
	 */
	public function get($pages){

        \elgg_set_ignore_access();
        $username = $pages[0];
        $user = new \Minds\entities\user($username);

        if($user->getEmail() == $pages[1]){
            $user->disabled_emails = true;
            $user->save();
        }


        $body = \elgg_view_layout('one_column', array(
            'title'=> 'Thanks, come back soon!',
            'content'=> 'You have now been unsubscribed from future emails'
        ));


         echo $this->render(array('body'=>$body));
    
    }
	
	public function post($pages){
	}
	
	public function put($pages){
	}
	
	public function delete($pages){
	}
	
}
