<?php
/**
 * Search plugin
 */
 
namespace minds\plugin\search;

use minds\core;
 
\elgg_register_event_handler('init', 'system', function(){
	new start();
});

class start extends \ElggPlugin{
	
	public function __construct(){
		parent::__construct('search');	
		
		$this->init();
	}
	
	public function init(){
		$routes = core\router::registerRoutes($this->registerRoutes());
		
		//makeshift indexer for testing
		/*foreach(elgg_get_entities(array('type'=>'user','limit'=>500)) as $entity){
			if($entity->access_id == 2)
				$this->createDocument($entity);
		}*/
		/*foreach(elgg_get_entities(array('type'=>'object','limit'=>500)) as $entity){
			if($entity->access_id == 2)
				$this->createDocument($entity);
		}*/
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
		);
	}
	
	/**
	 * Create a search document
	 */
	public function createDocument($entity){
		if(!in_array($entity->subtype, array('blog','image','album','kaltura_video')) || $entity->type != 'user')
			return false;

		$client = new \Elasticsearch\Client(array('hosts'=>array(\elgg_get_plugin_setting('server_addr','search'))));
		$params = array();
		$params['body']  = $entity->export();
		
		$params['index'] = 'minds';
		$params['type']  = $entity->type;
		$params['id']    = $entity->guid;
		
		// Document will be indexed to my_index/my_type/my_id
		$ret = $client->index($params);
		return $ret;
	}
	
}
