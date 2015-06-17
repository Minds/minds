<?php

namespace minds\plugin\cms\pages;

use Minds\Core;
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
		
		$header = false;
		$class = '';
		switch($pages[0]){
			case 'edit':
				try{
				    $page = new entities\page($pages[1]);
				    if($page->banner){
					$header = elgg_view('carousel/carousel', 
						array('items'=> array(
							new \ElggObject(array('ext_bg' => elgg_get_site_url().'p/header/'.$page->guid, 'top_offset'=>$page->banner_position))
						)));
					$class = "cms-banner cms-banner-editable";
				    }
				$content = elgg_view_form('cms/page', array('action'=>elgg_get_site_url().'p/edit/'.$page->guid, 'enctype'=>'multipart/form-data'), array('page'=>$page));
                                } catch (\Exception $e){
                                    echo $e->getMessage(); exit;
                                } 
				break;
			case 'add':
				$content = elgg_view_form('cms/page', array('action'=>elgg_get_site_url().'p/add', 'enctype'=>'multipart/form-data'), array('context'=>get_input('context', 'footer'))); 
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
					if($page->banner){
						$header = elgg_view('carousel/carousel', 
							array('items'=> array(
								new \ElggObject(array('ext_bg' => elgg_get_site_url().'p/header/'.$page->guid, 'top_offset'=>$page->banner_position))
							)));
						$class = "cms-banner";
					}
					$content .= $menu;
					$content .= elgg_view_title($title);
					$content .= elgg_view('cms/pages/body', array('body'=>$page->body));
				} catch(exceptions\notfound $e){
					$title = '404 - Page not found';
					$content = elgg_view('cms/pages/404');
				}
		}
		
		$body = elgg_view_layout('one_sidebar', array(
			'header'=> $header,
			'content'=>$content, 
			'sidebar_class'=>'elgg-sidebar-alt cms-sidebar-wrapper',
			'sidebar' => elgg_view('cms/pages/sidebar', array('context'=> 'footer')),
			'hide_ads' => true
		));
		
		elgg_extend_view('page/elements/foot', 'cms/footer');
		
		echo $this->render(array('title'=>$title, 'body'=>$body, 'class'=>'cms-page-body white-bg '. $class));
		
	}
	
	/**
	 * Post comments
	 */
	public function post($pages){

        if(!elgg_is_admin_logged_in()){
            exit;
        }

		if(isset($pages[1]))
			$page = new entities\page($pages[1]);
		else
			$page = new entities\page();
		
		if(is_uploaded_file($_FILES['banner']['tmp_name'])){
			if(!$page->guid)
				$page->save();
			
			$resized = get_resized_image_from_uploaded_file('banner', 2000);
			$file = new \ElggFile();
			$file->owner_guid = $page->owner_guid;
			$file->setFilename("cms/page/{$page->guid}.jpg");
			$file->open('write');
			$file->write($resized);
			$file->close();
			$page->banner = true;
		}
		
		$page->setTitle(get_input('title'))
			->setContext(get_input('context'))
			->setBody(get_input('body'))
			->setUri(get_input('uri', time()))
			->setForwarding(get_input('forwarding', false))
			->setBannerPosition(get_input('banner_position'))
			->save();
	
		$this->forward($page->getURL());
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
