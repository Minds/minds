<?php
/**
 * Blog lists
 */
namespace minds\plugin\blog\pages;

use minds\core;
use minds\interfaces;
use ElggBLog;

class lists extends core\page implements interfaces\page{
	
	public $context = 'blog';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		if(!isset($pages[0]))
			$pages[0] = 'featured';
		
		$params = array(
			'type' => 'object',
			'subtype' => 'blog',
			'limit' => 12,
			'offset' => get_input('offset', ''), 
			'full_view' => false,
			
			'title' => 'Blogs'
		);
		
		switch($pages[0]){
			case 'mine':
			case 'owner':
				break;
			case 'network':
				break;
			case 'trending';
				break;
			case 'featured':
			default:	
				$params['title'] = 'Featured Blogs';
				$guids = core\data\indexes::fetch('object:blog:featured', array('offset'=>get_input('offset', ''), 'limit'=>get_input('limit', 12)));
				$params['guids'] = $guids;
		}
	

		if(isset($params['guids']) && !$params['guids'])
			$content = '';
		else
			$content = core\entities::view($params);
		
		elgg_register_menu_item('filter', array(
			'name' => 'featured',
			'text' => elgg_echo('Featured'),
			'href' => "blog/list/featured",
			'selected' => $pages[0] == 'featured',
			'priority' => 1,
		));
		elgg_register_menu_item('filter', array(
                        'name' => 'trending',
                        'text' => elgg_echo('trending'),
                        'href' => "blog/trending",
                        'selected' => $pages[0] == 'trending',
                        'priority' => 2,
                ));
		
		$body = elgg_view_layout('gallery', array(
			'title'=>$params['title'],
			'content'=>$content,
			'filter' => elgg_view('page/layouts/content/filter', array('filter_context'=>$pages[0]))
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
