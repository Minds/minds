<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class index extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		\elgg_set_context('main');

		//elgg_generate_plugin_entities();

		// allow plugins to override the front page (return true to stop this front page code)
		if (\elgg_trigger_plugin_hook('index', 'system', null, FALSE) != FALSE) {
			exit;
		}

		if (\elgg_is_logged_in()) {
			\forward('activity');
		}

		$content = \elgg_view_title(\elgg_echo('content:latest'));

		$login_box = \elgg_view('core/account/login_box');

		$params = array(
				'content' => $content,
				'sidebar' => $login_box
		);

		$body = \elgg_view_layout('one_sidebar', $params);

		
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
