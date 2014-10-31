<?php
/**
 * Search page controller
 */
namespace minds\plugin\search\pages;

use minds\core;
use minds\interfaces;
use minds\entities;
use minds\plugin\search\services\elasticsearch;

class hack extends core\page implements interfaces\page {
        
        /**
         * Get requests
         */
        public function get($pages){
		\elgg_load_library('elgg:blog');
		$blog = new \ElggBlog(374026707536973824);
		$content = elgg_view_entity(new \ElggBlog(374026707536973824), array('full_view' => true));

		$body = \elgg_view_layout('content', array(
			'content'=>$content,
			'sidebar' => \blog_sidebar($blog)
		));
		echo $this->render(array('body'=>$body));
	}

		public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}

}
