<?php
/**
 * Market view page
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\market\entities;

class view extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		if($pages[0] == 'delete'){
			
			$item = new entities\item($pages[1]);
			return $item->delete();
			
		}
		
		$item = new entities\item($pages[0]);
		$content = elgg_view_entity($item, array('full_view'=>true));
		
		if($item->image){
			$header = elgg_view('carousel/carousel', 
						array('items'=> array(
							new \ElggObject(array(
								'ext_bg' => elgg_get_site_url()."market/image/$item->guid/master"
							))
						)));
		} else {
			$header = '';
		}
		
		$body = \elgg_view_layout('one_sidebar', array(
			'content' => $content,
			'sidebar' => elgg_view('market/sidebar'),
			'sidebar_class'=> 'elgg-sidebar-alt',
			'header'=> $header,
			'class'=> $header ? 'content-carousel' : ''
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
