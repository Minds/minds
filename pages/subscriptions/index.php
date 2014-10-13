<?php
/**
 * Minds subscriptions pages
 */
namespace minds\pages\subscriptions;

use minds\core;
use minds\interfaces;
use minds\entities;

class index extends core\page implements interfaces\page{
	
	public function get($pages){

		switch($pages[0]){
			case 'add':
				$content =  elgg_view_form('subscriptions/add', array('action'=>'subscriptions/subscribe'));
				break;
		}
		
		$body = \elgg_view_layout('tiles', array(
			'title'=>\elgg_echo('Subscriptions'), 
			'content'=>$content, 
			'filter_override' => elgg_view('channels/nav', array('selected' => $vars['page'])),			
		));
		
		echo $this->render(array('body'=>$body));
		
	}
	
	public function post($pages){
		
		switch($pages[0]){
			case 'subscribe':
				
				/**
				 * check if the user is on this node..
				 */
				$this_host = preg_replace('#^https?://#', '',elgg_get_site_url());
				if(strpos($_POST['address'], '@') === FALSE || strpos($_POST['address'], $this_host) !== FALSE){
					$address = str_replace(elgg_get_site_url(), '', $_POST['address']);
					$user = new entities\user($address);
					if(!$user->username){
						\register_error('Sorry, the user couldn\'t be found');
						$this->forward(REFERRER);
						return false;
					}
					
					
					if(core\session::getLoggedinUser()->subscribe($user->guid)){
									
						\system_message('Success!');
						
						$this->forward(REFERRER);
						return true;
					
					} else {
						
						\register_error('Sorry, there was a problem');
						
						$this->forward(REFERRER);
						return false;
						
					}
				}
				
				
				//send a request to the hosts site, to initiate subscription process
				try{
					$parts = explode('@', $_POST['address']);
					$data = core\clusters::call('POST', "https://$parts[1]", '/api/v1/subscriptions/subscribe/'.$parts[0], array_merge(core\session::getLoggedinUser()->export(), array(
								'guid'=>elgg_get_logged_in_user_guid(), 
								'host'=>elgg_get_site_url()
								)));
					

					$data['success']['host'] = "https://$parts[1]/";
					
					if(core\session::getLoggedinUser()->subscribe($data['success']['guid'], $data['success'])){
						\system_message('Success!');
						
						
						$this->forward(REFERRER);
						return true;
					}
					
					throw new \Exception('Sorry, there was a problem');
					
				}catch(\Exception $e){
					\register_error($e->getMessage());	
							
					$this->forward(REFERRER);
					return false;
				}
				break;
		}
		
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
