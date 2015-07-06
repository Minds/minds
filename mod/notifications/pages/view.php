<?php
/**
 * Notifications page handler
 */
namespace minds\plugin\notifications\pages;

use Minds\Core;
use minds\interfaces;

class view extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());

		$db = new \Minds\Core\Data\Call('entities_by_time');
		$guids = $db->getRow('notifications:'.$user_guid, array('limit'=> get_input('limit', 5), 'offset'=>get_input('offset','')));
		 \minds\plugin\notifications\notifications::resetCounter($user_guid);
		if(!$guids){
			echo 'Sorry, you don\'t have any notifications';
			return false;
		}
		$options = array(
			'guids'=>$guids,
			'limit' => get_input('limit', 12),
			'offset' => get_input('offset',''),
			'masonry' => false
		);

		if(!elgg_is_xhr()){
			
			gatekeeper();
			
			$title = elgg_echo('notifications');

			$content = elgg_list_entities($options);
			$params = array(
				'content' => $content,
				'title' => $title,
				'sidebar' => $sidebar,
				'filter_override' => '',
				'class' => 'notifications'
				);


			$body = elgg_view_layout('one_column', $params);
			
			echo \elgg_view_page($title, $body, 'default', array('class'=>'grey-bg'));
			
		} else {
			
			
				
			$user = elgg_get_logged_in_user_entity();
			
			if($user){
				$content = elgg_list_entities($options);
				
				echo $content;
			} else {
				
				echo elgg_echo('notifications:not_logged_in');
				
			}

		}
	}
	
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
