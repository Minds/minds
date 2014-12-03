<?php

namespace minds\plugin\cms\pages;

use minds\core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\cms\entities;
use minds\plugin\cms\exceptions;

class page extends core\page implements interfaces\page{
	
	public $context = 'cms';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		switch($pages[0]){
			case 'edit':
				$page = new entities\page($pages[1]);
				$content = elgg_view_form('cms/page', array('action'=>elgg_get_site_url().'p/edit/'.$page->guid), array('page'=>$page)); 
				break;
			case 'add':
				$content = elgg_view_form('cms/page', array('action'=>elgg_get_site_url().'p/add')); 
				break;
			case 'delete':
				$page = new entities\page($pages[1]);
				if($page->canEdit()){
					$page->delete();
				}
				$this->forward(REFERRER);
				break;
			default:
				try{
					$page = new entities\page($pages[0]);
					
					if($page->forwarding)
						$this->forward($page->forwarding);
					
					$menu = elgg_view_menu('entity', array(
						'entity'=>$page,
						'class' => 'elgg-menu-hz'
					));
					
					$title = $page->title;
					$content .= $menu;
					$content .= elgg_view_title($title);
					$content .= elgg_view('cms/pages/body', array('body'=>$page->body));
				} catch(exceptions\notfound $e){
					$title = '404 - Page not found';
					$content = elgg_view('cms/pages/404');
				}
		}
		
		$body = elgg_view_layout('one_sidebar', array(
			'content'=>$content, 
			'sidebar_class'=>'elgg-sidebar-alt cms-sidebar-wrapper',
			'sidebar' => elgg_view('cms/pages/sidebar', array('context'=> 'footer')),
			'hide_ads' => true
		));
		
		elgg_extend_view('page/elements/foot', 'cms/footer');
		
		echo $this->render(array('title'=>$title, 'body'=>$body, 'class'=>'cms-page-body'));
		
	}
	
	/**
	 * Post comments
	 */
	public function post($pages){
		
		if(isset($pages[1]))
			$page = new entities\page($pages[1]);
		else
			$page = new entities\page();
		
		$page->setTitle(get_input('title'))
			->setBody(get_input('body'))
			->setUri(get_input('uri', time()))
			->setForwarding(get_input('forwarding', false))
			->save();
	
		$this->forward($page->getURL());
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
