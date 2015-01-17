<?php
/**
 * Minds CMS Plugin
 * 
 * provides content management abilities to the site. 
 */

namespace minds\plugin\cms;

use Minds\Components;
use Minds\Core;

class start extends Components\Plugin{
	
	public function init(){
		
		\elgg_extend_view('css/elgg', 'css/cms');
		\elgg_extend_view('js/elgg', 'js/cms');
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\cms";
		core\router::registerRoutes(array(
				'/p' => "$path\\pages\\page",
				'/p/header' => "$path\\pages\\header",
				'/s' => "$path\\pages\\sections",
				'/admin/cms/sections' => "$path\\pages\\sections",
			));
		

		\elgg_register_plugin_hook_handler('output-extend', 'index', array($this, 'index'));

		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));
		\elgg_register_plugin_hook_handler('register', 'menu:entity',array($this, 'menuOverride'), 900);

		
		//\elgg_register_action('bitcoin/settings/save', dirname(__FILE__) . '/actions/plugins/settings/save.php', 'admin');
		//\elgg_register_admin_menu_item('configure', 'pages', 'cms');
	//	\elgg_register_admin_menu_item('configure', 'sections', 'cms');
	}
	
	
	/**
	 * Page setup (menus etc)
	 */
	public function pageSetup($event, $type, $params){
		
		$lu = new core\Data\lookup();
		$cacher = core\data\cache\factory::build();
		$hash = md5(elgg_get_site_url());

		if(!$footer = $cacher->get("$hash:cms:footer")){
			$footer = $lu->get("object:cms:menu:footer");
			$cacher->set("$hash:cms:footer", $footer);
		}

		if(!$topbar = $cacher->get("$hash:cms:topbar") && $topbar != 'not-set'){
			$topbar = $lu->get("object:cms:menu:topbar");
			$cacher->set("$hash:cms:topbar", $topbar ?: 'not-set');
		}
	
		/**
		 * Footer setup
		 */
		if($footer){
			foreach($footer as $path => $title){
				$edit_link = elgg_view('output/url', array('href'=>elgg_get_site_url() . 'p/edit/'.$path, 'text'=>'edit', 'class'=>'cms-sidebar-edit'));
				if(elgg_is_admin_logged_in())
					$text =  $title . $edit_link;
				else 
					$text = $title;
				
				elgg_register_menu_item('footer', array(
					'name' => $title,
					'href' => elgg_get_site_url() . 'p/'.$path,
					'text' => $text
				));
			}
		} else {
			foreach(array('about', 'terms', 'privacy') as $path){
				$title = ucwords($path);
				$page = new entities\page();
				$page->setTitle($title)
					->setBody('')
					->setUri($path)
					->save();
					
				elgg_register_menu_item('footer', array(
					'name' => $title,
					'href' => elgg_get_site_url() . 'p/'.$path,
					'text' => $title
				));
			}
		}
		
		if(elgg_is_admin_logged_in()){ 
			elgg_register_menu_item('footer', array(
				'name' => 'add',
				'href' => elgg_get_site_url() . 'p/add',
				'text' => '+ Add', 
				'priority'=>99999
			));
		}
		
		/**
		 * Topbar menu setup
		 */
		if($topbar){
			foreach($topbar as $path => $title){
				
				$text = $title;
				
				elgg_register_menu_item('topbar', array(
					'name' => $title,
					'href' => elgg_get_site_url() . 'p/'.$path,
					'text' => $text
				));
			}
		}
		
		if(elgg_is_admin_logged_in()){ 
			elgg_register_menu_item('topbar', array(
				'name' => 'add',
				'href' => elgg_get_site_url() . 'p/add?context=topbar',
				'text' => '+ Add', 
				'priority'=>99999
			));
		}
		

	}
	
	/**
	 * This is the homepage extension for the section blocks
	 * @todo implements hard caching as this is essentially just static content
	 */
	public function index($hook, $type, $return, $params){
		
		$guids = core\data\indexes::fetch('object:cms:sections:index', array('limit'=>1000));

		$add = '';
		if(elgg_is_admin_logged_in())		
			$add = '<div class="cms-section-add"><a href="#" data-group="index"> + Add a panel </a></div>';

		if(!$guids)
			$sections = array();
		else
			$sections = core\entities::get(array('guids'=>$guids));
		$return .= elgg_view('cms/sections', array('sections'=>$sections, 'group'=>'index'));
		$return .= $add;
		
		elgg_extend_view('page/elements/foot', 'cms/footer');
		
		return $return;
	}

	public function menuOverride($hook, $type, $return, $params){
		if(isset($params['entity']) &&  $params['entity']->subtype == 'cms_page'){
		
			$entity = $params['entity'];
			foreach($return as $k => $item){
				if(in_array($item->getName(), array('access', 'feature', 'thumbs:up', 'thumbs:down', 'delete')))
					unset($return[$k]);
			}
		
			if($entity->canEdit()){	
				$options = array(
								'name' => 'edit',
								'href' => "p/edit/$entity->uri",
								'text' => 'Edit',
								'title' => elgg_echo('edit'),
								'priority' => 1,
							);
				$return[] = \ElggMenuItem::factory($options);	
				
				$options = array(
								'name' => 'delete',
								'href' => "p/delete/$entity->uri",
								'text' => 'Delete',
								'title' => elgg_echo('delete'),
								'class'=>'ajax-non-action'
							);
				$return[] = \ElggMenuItem::factory($options);	
				
			}
			return $return;
		}
	}
	
}
