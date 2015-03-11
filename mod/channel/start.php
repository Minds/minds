<?php
/**
 * Minds Channel Profiles
 *
 * @package channel
 */
namespace minds\plugin\channel;

use Minds\Core;
use Minds\Api;

class start extends \ElggPlugin{
	
	/**
	 * Init function
	 */
	public function init(){
		
		global $CONFIG;
		$CONFIG->minusername = 2;
		
		if(isset($_COOKIE['_elgg_to_friend']) && elgg_is_logged_in()){
			$friend = elgg_get_logged_in_user_entity()->addFriend($_COOKIE['_elgg_to_friend']);
			if($friend instanceof ElggUser){
				forward($friend->getURL());
			}
		}
		
		elgg_register_menu_item('site', array(
			'name' => 'channels',
			'text' => '<span class="entypo">&#59254;</span> Directory',
			'href' => 'channels/featured',
			'title' => elgg_echo('channels'),
			'priority' => 2
		));
		
		core\router::registerRoutes(array(
			'/channel' => "\\minds\\plugin\\channel\\pages\\channel",
			'/channels' => "\\minds\\plugin\\channel\\pages\\directory",
			'/directory' => "\\minds\\plugin\\channel\\pages\\directory",
			
           // '/api/v1/channel' =>  "\\minds\\plugin\\channel\\api\\v1\\channel"
		));
        
	        Api\Routes::add('v1/channel', "\\minds\\plugin\\channel\\api\\v1\\channel");
        	\Minds\Core\Events\Dispatcher::register('export:extender', 'all', function($event){
		    $params = $event->getParameters();
		    $export = array();
		    if($params['entity']->ownerObj){
			$export['ownerObj'] = $params['entity']->ownerObj;
			$export['ownerObj']['guid'] = (string) $params['entity']->ownerObj['guid'];	
		        $event->setResponse($export);
	     	    }
		});
		
		/**
		 * Returns the url.. this should really be in models/entities now
		 */
		elgg_register_entity_url_handler('user', 'all', function($user){
			//if($user->base_node)
			//	return $user->base_node. $user->username;
			//else 
				return elgg_get_site_url() . $user->username;
		});

		elgg_register_plugin_hook_handler('entity:icon:url', 'user', array($this, 'avatarURL'));
		elgg_unregister_plugin_hook_handler('entity:icon:url', 'user', 'user_avatar_hook');
		
		elgg_register_plugin_hook_handler('register', 'menu:hovercard', array($this, 'hoverMenuSetup'));
		
		//setup the channel elements menu content with defaults
		elgg_register_plugin_hook_handler('register', 'menu:channel_elements', function($hook, $type, $return, $params) {
			$user = elgg_get_page_owner_entity();
		
			//archive
			$url = "archive/owner/$user->username/";
			$item = new ElggMenuItem('archive', elgg_echo('archive'), $url);
			$item->setPriority(1);
			$return[] = $item;
			
			//blogs
			$url = "blog/owner/$user->username/";
			$item = new ElggMenuItem('blog', elgg_echo('blog'), $url);
			$item->setPriority(2);
			$return[] = $item;

			return $return;
		});
		
		elgg_register_library('channels:suggested', elgg_get_plugins_path() . 'channel/lib/suggested.php');
		
		elgg_register_page_handler('profile', 'channel_page_handler');
		elgg_register_page_handler('channel', function($pages){
			$router = new core\router();
			$path = implode('/', $pages);
			$router->route('/channel/'.$path);
			return true;
		});
		
		elgg_extend_view('page/elements/head', 'channel/metatags');
		elgg_extend_view('css/elgg', 'channel/css');
		elgg_extend_view('js/elgg', 'channel/js');
		
		//still require these?
		elgg_register_js('minicolors', elgg_get_site_url() . 'vendor/abeautifulsite/jquery-minicolors/jquery.minicolors.min.js','footer');
		elgg_register_css('minicolors', elgg_get_site_url() . 'vendor/abeautifulsite/jquery-minicolors/jquery.minicolors.css');
	
	
		elgg_register_page_handler('icon', function($pages){
			$_GET['guid'] = $pages[0];
			$_GET['size'] = $pages[1];
			$_GET['joindate'] = $pages[2];
			$_GET['lastcache'] =  $page[3];
			include('icondirect.php');
			return true;
		});
		
		//set a new file size
		elgg_set_config('icon_sizes', array(	
			'topbar' => array('w'=>16, 'h'=>16, 'square'=>TRUE, 'upscale'=>TRUE),
			'tiny' => array('w'=>25, 'h'=>25, 'square'=>TRUE, 'upscale'=>TRUE),
			'small' => array('w'=>40, 'h'=>40, 'square'=>TRUE, 'upscale'=>TRUE),
			'medium' => array('w'=>100, 'h'=>100, 'square'=>TRUE, 'upscale'=>TRUE),
			'large' => array('w'=>425, 'h'=>425, 'square'=>FALSE, 'upscale'=>FALSE),	
			//'xlarge'=> array('w'=>400, 'h'=>400, 'square'=>false, 'upscale'=>false),
			'master' => array('w'=>550, 'h'=>550, 'square'=>FALSE, 'upscale'=>FALSE),
		));
		
		if(elgg_get_context() == 'channel' || elgg_get_context() == 'avatar' || elgg_get_context() == 'profile'){
			elgg_register_menu_item('page', array(	
				'name' => 'backtochannel',
				'text' => elgg_echo('channel:return'),
				'href' => 'channel/' . elgg_get_logged_in_user_entity()->username,
			));		
			elgg_register_menu_item('page', array(	
				'name' => 'custom_channel',
				'text' => elgg_echo('channel:custom'),
				'href' => 'channel/' . elgg_get_logged_in_user_entity()->username . '/custom'
			));		
		}
	
		elgg_register_action("channel/custom", elgg_get_plugins_path() . "channel/actions/custom.php");
		
	}

	/**
	 * Hovermenu setup
	 */
	public function hoverMenuSetup($hook, $type, $return, $params) {
		$user = $params['entity'];
		if (elgg_is_logged_in() && (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $user->guid)) {
			$url = "channel/$user->username/custom/";
			$item = new \ElggMenuItem('send', elgg_echo('channel:custom'), $url);
			$item->setSection('action');
			//$item->setLinkClass('elgg-lightbox');
			$return[] = $item;
			
			$options = array(
						'name' => 'feature',
						'href' => "action/minds/feature?guid=$user->guid",
						'text' => $user->featured_id ? elgg_echo('un-feature') : elgg_echo('feature'),
						'title' => elgg_echo('feature'),
						'is_action' => true,
						'section'=>'admin',
						'priority' => 2,
					);
			$return[] = \ElggMenuItem::factory($options);
		}
	
		return $return;
	}
	
	
	/**
	 * Use a URL for avatars that avoids loading Elgg engine for better performance
	 *
	 * @param string $hook
	 * @param string $entity_type
	 * @param string $return_value
	 * @param array  $params
	 * @return string
	 */
	public function avatarURL($hook, $entity_type, $return_value, $params) {
		global $CONFIG;
	
		if ($return_value) {
			return null;
		}
		
		$user = $params['entity'];
		$size = $params['size'];
		
		if (!elgg_instanceof($user, 'user')) {
			return null;
		}
	
		if($user->avatar_url)
			return $user->avatar_url;
	
		$user_guid = $user->getGUID();
		$icon_time = $user->icontime;
	
		if($user->legacy_guid){
			$user_guid = $user->legacy_guid;
		}
	
		$join_date = $user->getTimeCreated();
		//return $CONFIG->cdn_url .  "mod/channel/icondirect.php?lastcache=$icon_time&joindate=$join_date&guid=$user_guid&size=$size";
		
		if($user->base_node)
			return $user->base_node . "icon/$user_guid/$size/$join_date/$icon_time/".$CONFIG->lastcache;
		else
			return  $CONFIG->cdn_url . "icon/$user_guid/$size/$join_date/$icon_time/".$CONFIG->lastcache;
	}
	
	
	static public function channel_custom_vars($user = null) {
		// input names => defaults
		$values = array(
			'background' => null,
			'background_colour' => '#FAFAFA',
			'background_repeat' => 'repeat',
			'background_attachment' => 'fixed',
			
			'h1_colour' => '#333',
			'h3_colour' => '#333',
	
			'menu_link_colour' => '#333',
	
	
			'briefdescription' => '',
			'description' => '',
			'contactemail' => '',
			'location' => '',
			'website' => '',
			
			'social_link_fb' => '',
			'social_link_gplus' => '',
			'social_link_twitter' => '',
			'social_link_tumblr' => '',
			'social_link_linkedin' => '',		
			'social_link_github' => '',
			'social_link_pinterest' => '',
			'social_link_instagram' => '',
			'social_link_youtube' => ''
		);
	
		if($user){
			foreach (array_keys($values) as $field) {
				if (isset($user->$field)) {
					$values[$field] = $user->$field;
				}
			}
		}
	
		return $values;
	}
	
}
