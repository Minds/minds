<?php
/**
 * Minds gatherings.
 * 
 * @package Minds
 * @subpackage gatherings
 * @author Mark Harding (mark@minds.com)
 * 
 */

namespace minds\plugin\gatherings;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	public function init(){
		
		if(elgg_is_logged_in() && false){
	
			elgg_register_menu_item('site', array(	'name'=>'gathering',
								'title'=>elgg_echo('gatherings:menu:site'),
								'href'=>'gatherings/all',
								'text' => '<span class="entypo">&#59160;</span> Gatherings',
								'priority' => 150	
						));
		}

		/*add_subtype("object", "gathering", "MindsGathering");  
			
		elgg_register_library('bblr', elgg_get_plugins_path() . 'gatherings/vendors/bblr-php-sdk/bblr.php');
		elgg_register_library('minds:gatherings', elgg_get_plugins_path() . 'gatherings/lib/gatherings.php');
		
		// Register a url handler for the new object
		elgg_register_entity_url_handler('object', 'gathering', 'gatherings_url');*/
		
		\elgg_extend_view('page/elements/foot', 'gatherings/bar');
		\elgg_extend_view('css/elgg', 'gatherings/css');
		\elgg_extend_view('js/elgg', 'js/gatherings/live');

		elgg_register_js('portal', elgg_get_site_url() . 'mod/gatherings/vendors/portal.js');
		elgg_load_js('portal');
		
		elgg_register_js('swfobject', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/swfobject.js', 'footer',599);
		elgg_load_js('swfobject');
		
		elgg_register_js('wraprtc', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/wraprtc.js', 'footer', 700);
		elgg_load_js('wraprtc');

		core\router::registerRoutes(array(
			'/gatherings' => "\\minds\\plugin\\gatherings\\pages\\gatherings"
		));

		// Register a page handler, so we can have nice URLs
		elgg_register_page_handler('gatherings',array($this, 'pageHandler'));
			
		// Register some actions
		$action_base = elgg_get_plugins_path() . 'gatherings/actions/gatherings';
		elgg_register_action("gatherings/join", "$action_base/join.php");
		elgg_register_action("gatherings/save", "$action_base/save.php");
		elgg_register_action("gatherings/delete", "$action_base/delete.php");
	
		
	}

	/**
	 * Encryptor 
	 */
	public function encrypt($message){
		$user = \elgg_get_logged_in_user_entity();
		
	}
	 

}
