<?php
/**
 * Minds contact page
 */
namespace minds\pages;

use minds\core;
use minds\interfaces;

class contact extends core\page implements interfaces\page{
	
	public function get($pages){
		
		$content = \elgg_view_form('contact', array('action'=>'contact'));
		
		$body = \elgg_view_layout('one_column', array('title'=>\elgg_echo('guard:twofactor'), 'content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){

		if($_POST['time'] != ''){
			\register_error('sorry, wrong answer');
			return false;
		}
			
		\elgg_send_email($_POST['email'], array('mark@minds.com','bill@minds.com'), 'New Email from ' . $_POST['name'], $_POST['message']);
		
		\system_message('Success!');
		
		$this->get($pages);
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
