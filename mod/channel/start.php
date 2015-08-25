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

		elgg_register_menu_item('site', array(
			'name' => 'channels',
			'text' => '<span class="entypo">&#59254;</span> Directory',
			'href' => 'channels/featured',
			'title' => elgg_echo('channels'),
			'priority' => 2
		));

		core\router::registerRoutes(array(
      '/profile' => "\\minds\\plugin\\channel\\pages\\channel",
      '/channel' => "\\minds\\plugin\\channel\\pages\\channel",
			'/channels' => "\\minds\\plugin\\channel\\pages\\directory",
			'/directory' => "\\minds\\plugin\\channel\\pages\\directory"
		));

	  Api\Routes::add('v1/channel', "\\minds\\plugin\\channel\\api\\v1\\channel");
		\Minds\Core\Events\Dispatcher::register('export:extender', 'all', function($event){
		    $params = $event->getParameters();
		    $export = array();
		    if($params['entity']->ownerObj && is_array($params['entity']->ownerObj)){
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

	}

}
