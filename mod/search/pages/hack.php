<?php
/**
 * Search page controller
 */
namespace minds\plugin\search\pages;

use Minds\Core;
use minds\interfaces;
use minds\entities;
use minds\plugin\search\services\elasticsearch;

class hack extends core\page implements interfaces\page {
        
        /**
         * Get requests
         */
        public function get($pages){
		\elgg_load_library('elgg:blog');
//		$blog = new \ElggBlog(374026707536973824);


//		$content = elgg_view_entity($blog, array('full_view' => true));

		$page = new \minds\plugin\cms\entities\page('about');
		$title = $page->title;
					$content .= $menu;
					$content .= elgg_view('cms/pages/body', array('body'=>$page->body));
		$body = \elgg_view_layout('content', array(
			'title' => $title,
			'content'=>$content,
			'sidebar' => elgg_view('page/elements/ads', array('content-side-single'))
		));
		echo $this->render(array('body'=>$body, 'title'=>$title));
	}

		public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}

}
