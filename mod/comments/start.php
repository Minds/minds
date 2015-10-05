<?php
/**
 * Minds Comments
 *
 * @author Mark Harding (mark@minds.com)
 */

namespace minds\plugin\comments;

use Minds\Components;
use Minds\Api;
use Minds\Core;

class start extends Components\Plugin{

  /**
	 * Initialise the plugin
	 */
	public function init(){
    Api\Routes::add('v1/comments', "minds\\plugin\\comments\\api\\v1\\comments");

		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			//var_dump($row);
			if($row->type == 'comment')
				return new entities\comment($row);
		});

    Core\Events\Dispatcher::register('export:extender', 'all', function($event){
        $params = $event->getParameters();
        $export = array();
        $cacher = Core\Data\cache\factory::build();
				if($params['entity']->type != 'activty')
					return false;

        $db = new Core\Data\Call('entities_by_time');
				if($params['entity']->entity_guid){
					$guid = $params['entity']->entity_guid;
				} else {
				  $guid = $params['entity']->guid;
				}

				$cached = $cacher->get("comments:count:$guid");
				if($cached !== FALSE){
					$count = $cached;
				} else {
					$count = $db->countRow("comments:$guid");
					$cacher->set("comments:count:$guid", $count);
				}


        $export['comments:count'] = $count;
        $event->setResponse($export);
    });

		\elgg_register_plugin_hook_handler('register', 'menu:comments', array($this,'menu'));

	}

}
