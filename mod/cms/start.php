<?php
/**
 * Minds CMS Plugin
 * 
 * provides content management abilities to the site. 
 */

namespace minds\plugin\cms;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	public function init(){
		
		\elgg_extend_view('css/elgg', 'css/cms');
		\elgg_extend_view('js/elgg', 'js/cms');
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\cms";
		core\router::registerRoutes(array(
				'/p' => "$path\\pages\\page",
				'/s' => "$path\\pages\\sections",
				'/admin/cms/sections' => "$path\\pages\\sections",
			));
		

		\elgg_register_plugin_hook_handler('output-extend', 'index', array($this, 'index'));

		//\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));

		
		//\elgg_register_action('bitcoin/settings/save', dirname(__FILE__) . '/actions/plugins/settings/save.php', 'admin');
		//\elgg_register_admin_menu_item('configure', 'pages', 'cms');
	//	\elgg_register_admin_menu_item('configure', 'sections', 'cms');
	}
	
	
	/**
	 * Page setup (menus etc)
	 */
	public function pageSetup(){
		if(elgg_get_context() == 'bitcoin'){
			
			\elgg_register_menu_item('page', array(
			    'name' => 'bitcoin',
			    'text' => '<span class="entypo">&#59408;</span> My Wallet',
			    'href' => 'bitcoin/wallet',
			    'title' => elgg_echo('bitcoin')
		    ));
			
			\elgg_register_menu_item('page', array(
			    'name' => 'bitcoin:send',
			    'text' => 'Send',
			    'href' => 'bitcoin/send',
			    'title' => elgg_echo('bitcoin:send')
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

		$sections = core\entities::get(array('guids'=>$guids));
		$return .= elgg_view('cms/sections', array('sections'=>$sections, 'group'=>'index'));
		$return .= $add;
		
		$return .= elgg_view('cms/footer');
		
		return $return;
	}

	
}
