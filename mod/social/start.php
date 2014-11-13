<?php
/**
 * Social 
 */

namespace minds\plugin\social;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	static public $services = array('facebook', 'twitter');
	
	public function init(){
		
		\elgg_extend_view('css/elgg', 'css/social');
		\elgg_extend_view('js/elgg', 'js/social');
	
		if(elgg_is_logged_in())	
			\elgg_extend_view('forms/activity/post', 'social/form_extend');
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\social";
		core\router::registerRoutes(array(
				'/plugin/social/redirect' => "$path\\pages\\redirect",
			));
		

		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));
		\elgg_register_event_handler('create', 'activity', array($this, 'postHook'));

	}
	
	
	/**
	 * Page setup (menus etc)
	 */
	public function pageSetup($event, $type, $params){

	}
	
	/**
	 * Post all activity posts from the newsfeed to the connected social networks, if linked
	 * 
	 * @param string $event - create
	 * @param string $type - activity
	 * @param object $activity - the activty object/model
	 * @return void 
	 */
	public function postHook($event, $type, $activity){
		if($activity instanceof \minds\entities\activity){
			try{
				if(isset($_REQUEST['social_triggers'])){
					foreach($_REQUEST['social_triggers'] as $service => $selected){
						if($selected == 'selected')
							services\build::build($service)->post( $activity->export()); 
					}
				}
			} catch(\Exception $e){var_dump($e);exit;}
		}
	}
	
	static public function userConfiguredServices($user = NULL){
		if(!$user)
			$user = core\session::getLoggedinUser();
		
		$services = array();
		foreach(self::$services as $service){
			if(\elgg_get_plugin_user_setting("$service", $user->guid,'social') == 'enabled')
				$services[] = $service;
		}
		return $services;
	}

	static public function setMetatags($name, $content){

		global $SOCIAL_META_TAGS;

		$strip = strip_tags($content);
		$SOCIAL_META_TAGS[$name]['property'] = $name;
		$SOCIAL_META_TAGS[$name]['content'] = strip_tags($content);

		return;

	}


}
