<?php
/**
 * Minds newsfeed feed page
 */
namespace minds\pages\newsfeed;

use minds\core;
use minds\entities;
use minds\interfaces;

class newsfeed extends core\page implements interfaces\page{
	
	public $context = 'newsfeed';
	
	public function get($pages){
		
		if(get_input('new')){
			$activity = new entities\activity();
			$activity->setTitle('This is a rich post')
					->setBlurb('and this is is the description for it. this should go to bbc when clicked')
					->setURL('https://www.bbc.co.uk/news')
					->save();
		}
		
		$post = elgg_view_form('activity/post', array('action'=>'newsfeed/post'));
		
		$content .= core\entities::view(array(
			'type' => 'activity',
			'masonry' => false,
			'prepend' => $post,
			'list_class' => 'list-newsfeed'
		));
		
		$sidebar_left = elgg_view('channel/sidebar', array(
			'user' => elgg_get_logged_in_user_entity()
		));
		
		$sidebar_right = "welcome";
		
		$body = \elgg_view_layout('two_sidebar', array(
			'title'=>\elgg_echo('newsfeed'), 
			'content'=>$content, 
			'sidebar'=>$sidebar_right, 
			'sidebar_alt'=>$sidebar_left,
			'sidebar-alt-class' =>  'minds-fixed-sidebar-left'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){

		switch($pages[0]){
			case 'post':
				$activity = new entities\activity();
				if(isset($_POST['message']))
					$activity->setMessage($_POST['message']);
				
				if(isset($_POST['title'])){
					$activity->setTitle('This is a rich post')
						->setBlurb('and this is is the description for it. this should go to bbc when clicked')
						->setURL('https://www.bbc.co.uk/news');
				}
				
				$activity->save();
				$this->forward('newsfeed');
				exit;
		}
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
