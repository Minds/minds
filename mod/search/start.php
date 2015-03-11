<?php
/**
 * Search plugin
 */
 
namespace minds\plugin\search;

use Minds\Core;

class start extends \minds\Components\Plugin{
	
	public function init(){
		$routes = core\router::registerRoutes($this->registerRoutes());
		\elgg_extend_view('css/elgg', 'search/css');

		\elgg_register_js('search', \elgg_get_simplecache_url('js', 'search'));
		\elgg_load_js('search');
		
		//makeshift indexer for testing
		/*foreach(elgg_get_entities(array('type'=>'user','limit'=>500)) as $entity){
			if($entity->access_id == 2)
				$this->createDocument($entity);
		}*/
		/*foreach(elgg_get_entities(array('type'=>'object','limit'=>500)) as $entity){
			if($entity->access_id == 2)
				$this->createDocument($entity);
		}*/
		\elgg_register_event_handler('create', 'user', array($this, 'hook'));
		\elgg_register_event_handler('create', 'object', array($this, 'hook'));
	}
	
	/**
	 * Handler the pages
	 * 
	 * @param array $pages - the page slugs
	 * @return bool
	 */
	public function registerRoutes(){
		$path = "minds\\plugin\\search";
		return array(
			'/search' => "$path\\pages\\search",
			'/search/result' => "$path\\pages\\hack"
		);
	}
	
	/**
	 * Create a search document
	 */
	public function createDocument($entity){
		global $CONFIG;
		error_log("attempting index of $entity->type");
		if(in_array($entity->subtype, array('blog','image','album','video')) || $entity->type == 'user' || $entity->type == 'activity'){

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
		error_log("create hook fired ");
		if($entity && $entity->access_id == 2){
			try{
				$this->createDocument($entity);
			} catch(\Exception $e){
				
			}
		}
	}
	
}
