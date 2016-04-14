<?php
/**
 * Search plugin
 */

namespace minds\plugin\search;

use Minds\Api;
use Minds\Core;

class start extends \minds\Components\Plugin{

	public function init(){

    //Api\Routes::add('v1/search', '\\minds\\plugin\\search\\api\\v1\\search');

		// \elgg_register_event_handler('create', 'user', array($this, 'hook'));
		// \elgg_register_event_handler('create', 'group', array($this, 'hook'));
		// \elgg_register_event_handler('create', 'object', array($this, 'hook'));
	}

	/**
	 * Create a search document
	 */
	public function createDocument($entity){
		global $CONFIG;
		//error_log("attempting index of $entity->type");
		if(in_array($entity->subtype, array('blog','image','album','video')) || $entity->type == 'user' || $entity->type == 'group' || $entity->type == 'activity'){

			$client = new \Elasticsearch\Client(array('hosts'=>array(\elgg_get_plugin_setting('server_addr','search')?:'localhost')));
			$params = array();
			$data = $entity->export();
			foreach($data as $k =>$v){
				if(is_numeric($v))
					$v = (string) $v;

				if(is_bool($v))
					continue;

				$params['body'][$k]  = $v;
			}

			$params['index'] = $CONFIG->cassandra->keyspace;
			$params['type']  = $entity->type;
			$params['id']    = $entity->guid;

			// Document will be indexed to my_index/my_type/my_id
			$ret = $client->index($params);
			return $ret;
		} else {
			return false;
		}
	}

	public function hook($hook, $type, $entity, $params = array()){
		if($entity && $entity->access_id == 2){
			try{
				$this->createDocument($entity);
			} catch(\Exception $e){

			}
		}
	}

}
