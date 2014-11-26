<?php
/**
 * Gatherings page handler
 */
namespace minds\plugin\gatherings\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;

class conversations extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	
	/**
	 * Reading messages and getting lists of messages
	 */
	public function get($pages){
		
		$content = elgg_view('gatherings/conversations/welcome');
		
		$conversations = \minds\plugin\gatherings\start::getConversationsList();	
				
		$layout = elgg_view_layout('one_sidebar_alt', array('content'=>$content, 'sidebar'=>elgg_view('gatherings/conversations/list', array('conversations'=>$conversations))));
		echo $this->render(array('body'=>$layout));
		
	}

	public function post($pages){}

	public function put($pages){}

	public function delete($pages){}
	
}
