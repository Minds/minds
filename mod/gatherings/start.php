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

use Minds\Components;
use Minds\Core;

class start extends Components\Plugin{
	
	public function init(){
		
		if(elgg_is_logged_in() && false){
			elgg_register_menu_item('site', array(	'name'=>'gathering',
					'title'=>elgg_echo('gatherings:menu:site'),
					'href'=>'gatherings/all',
					'text' => '<span class="entypo">&#59160;</span> Gatherings',
					'priority' => 150	
			));
		}

		if(elgg_is_logged_in()){
			 elgg_register_menu_item('site', array(  
					'name'=>'gathering',
                                        'title'=>elgg_echo('gatherings:chat'),
                                        'href'=>'gatherings/conversations',
                                        'text' => '<span class="entypo">&#59168;</span> Messenger',
                                        'priority' => 150
                        ));
		}
		
		if (\elgg_is_logged_in())
			\elgg_extend_view('page/elements/topbar/right/actions', 'gatherings/topbar_icon');
		
		\elgg_extend_view('page/elements/foot', 'gatherings/bar');
		\elgg_extend_view('css/elgg', 'gatherings/css');
		
		\elgg_extend_view('js/initialize_elgg', 'js/init');
		\elgg_extend_view('js/elgg', 'js/gatherings/live');
		\elgg_extend_view('js/elgg', 'js/gatherings/stored');
		\elgg_extend_view('js/elgg', 'js/gatherings/crypt');
		
		elgg_register_js('jcryption', elgg_get_site_url() . 'mod/gatherings/vendors/jcryption.js');
		elgg_load_js('jcryption');

		elgg_register_js('portal', elgg_get_site_url() . 'mod/gatherings/vendors/portal.js');
		elgg_load_js('portal');
		
		elgg_register_js('swfobject', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/swfobject.js', 'footer',599);
		elgg_load_js('swfobject');
		
		elgg_register_js('wraprtc', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/wraprtc.js', 'footer', 700);
		elgg_load_js('wraprtc');

		core\router::registerRoutes(array(
			'/gatherings' => "\\minds\\plugin\\gatherings\\pages\\gatherings",
			'/gatherings/configuration' => "\\minds\\plugin\\gatherings\\pages\\configuration",
			'/gatherings/conversation' => '\\minds\\plugin\\gatherings\\pages\\conversation',
			'/gatherings/conversations' => '\\minds\\plugin\\gatherings\\pages\\conversations',
			'/gatherings/decrypt' => '\\minds\\plugin\\gatherings\\pages\\decrypt',
			'/gatherings/unlock' => '\\minds\\plugin\\gatherings\\pages\\unlock',
			'/gatherings/live' => '\\minds\\plugin\\gatherings\\pages\\live',
			
            //@todo debate whether this is the best way to load api endpoints
            '/api/v1/conversations' => '\\minds\\plugin\\gatherings\\api\\v1\\conversations',
            '/api/v1/keys' => '\\minds\\plugin\\gatherings\\api\\v1\\keys'
		));
		
		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			//var_dump($row);
			if($row->subtype == 'message')
				return new entities\message($row);
		});
		
		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));

		// Register a page handler, so we can have nice URLs
		elgg_register_page_handler('gatherings',array($this, 'pageHandler'));
			
		// Register some actions
		$action_base = elgg_get_plugins_path() . 'gatherings/actions/gatherings';
		elgg_register_action("gatherings/join", "$action_base/join.php");
		elgg_register_action("gatherings/save", "$action_base/save.php");
		elgg_register_action("gatherings/delete", "$action_base/delete.php");
	
		\elgg_register_plugin_hook_handler('acl', 'all', array($this, 'acl'));
		
	}

	/**
	 * Encryptor 
	 */
	public function encrypt($message){
		$user = \elgg_get_logged_in_user_entity();
		
	}
	
	/**
	 * Sets the sidebar menus
	 */
	public function pageSetup(){
		if(elgg_get_context() == 'gatherings'){
			
		}
	}

	static public function getConversationsList(){
		$conversation_guids = core\data\indexes::fetch("object:gathering:conversations:".elgg_get_logged_in_user_guid(), array('limit'=>50));
		if($conversation_guids){
			$conversations = array();
			
			arsort($conversation_guids);
			
			foreach($conversation_guids as $user_guid => $data){
				if(is_numeric($data)){
					$ts = $data;
					$unread = 0;
				} else {
					$data = json_decode($data, true);
					$unread = $data['unread'];
					$ts = $data['ts'];
				}
				$u = new \minds\entities\user($user_guid);
				$u->last_msg = $ts;
				$u->unread = $unread;
				if($u->username){
					$conversations[] = $u;
				}
				continue;
			}
			
		}
		return $conversations;
	}


	/**
	 * Extends the acl to allow access to message users are supposed to see
	 */
	public function acl($event, $type, $return, $params){
		
		$message = $params['entity'];
		$user = $params['user'];

		if($message instanceof \minds\plugin\gatherings\entities\message){
			$key = "message:$user->guid";
			if($message->$key)
				return true;
		}

		return $return;

	}	 

}
