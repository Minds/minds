<?php
/**
 * Minds contact page
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class contact extends core\page implements interfaces\page{
	
	public function get($pages){
		
		$content = \elgg_view_form('contact', array('action'=>'contact'));
		
		$body = \elgg_view_layout('one_sidebar', array(
			'title'=>\elgg_echo('guard:twofactor'), 
			'content'=>$content,
			'sidebar'=>elgg_view('cms/pages/sidebar'),
			'sidebar_class' => 'elgg-sidebar-alt cms-sidebar-wrapper',
			'hide_ads'=>true
		));

		elgg_extend_view('page/elements/foot', 'cms/footer');
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){

		if($_POST['time'] != ''){
			\register_error('sorry, wrong answer');
			return false;
		}

		$contact = array($_POST['email'],'mark@minds.com','bill@minds.com', elgg_get_site_entity()->getEmail());
		\elgg_send_email('emails@minds.com', $contact, 'New Email from ' . $_POST['name'] . ' ' . $_POST['email'], $_POST['message']);
		
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
