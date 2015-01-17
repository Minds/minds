<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class test extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		if(!$pages){
			echo 'this is a get request';
		} else {
			echo 'you have '.count($pages) . ' pages';
		}
		return;
		$body = \elgg_view_layout('one_column', array(
			'content'=>'testing'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		echo 'this is a post request';
	}
	
	public function put($pages){
		echo 'this is a put request';
	}
	
	public function delete($pages){
		echo 'this is a delete request';
	}
	
}
