<?php
/**
 * Search page controller
 */
namespace minds\plugin\search\pages;

use Minds\Core;
use minds\interfaces;
use minds\entities;
use minds\plugin\search\services\elasticsearch;

class search extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
	
		if(isset($pages[0]) && $pages[0] == 'index'){
			foreach(\elgg_get_entities(array('type'=>'user','limit'=>500)) as $entity){
				if($entity->access_id == 2)
					\minds\plugin\search\start::createDocument($entity);
			}
			foreach(\elgg_get_entities(array('type'=>'object','limit'=>500)) as $entity){
				if($entity->access_id == 2)
					\minds\plugin\search\start::createDocument($entity);
			}
			echo 'done';
			exit;
		}
		
		global $CONFIG;
		$client = new \Elasticsearch\Client(array('hosts'=>array(\elgg_get_plugin_setting('server_addr','search')?:'localhost')));
		$params = array();
		
		$query = $_GET['q'];
		$query = preg_replace("/[^A-Za-z0-9 ]/", ' ',$query);	
		if(!$query){
			exit;
		}
        $query = "*$query*";
        $category = \get_input('category');
		if($category)
			$query = "query AND $category";
		
		if(get_input('subtype'))
			$query .= ' +subtype:"'.get_input('subtype') .'"';

		$body['query']['query_string']['query'] = $query;
		$body['query']['query_string']['fields'] = array('_all', 'name^5', 'title^8', 'username^16');
			
		$params['index'] = $CONFIG->cassandra->keyspace; //we use the keyspace as this is unique to each site. why complicate things?
		
		if(isset($pages[0]))
			$params['type'] = $pages[0];
		
		if(get_input('type'))
			$params['type'] = get_input('type');
		
		$params['size'] = \get_input('limit');
        if(\get_input('offset'))
		$params['from'] = \get_input('offset');
		$params['body']  = $body;
		try{
			$results = $client->search($params);
                	$guids = array();
			foreach($results['hits']['hits'] as $raw){
                        	array_push($guids, $raw['_id']);
            		}
		}catch(\Exception $e){
			$guids = array();
		}
		if(empty($guids)){
			$content = \elgg_view('search/filter');
			$content .= '<div style="padding:16px; margin:42px 2%; width:400px;font-weight:bold; font-size:24px;">Sorry, no results could be found</div>';
		} else {
			//$entities = \elgg_get_entities(array('guids'=>$guids));
			//$content = \elgg_view('search/list', array('entities'=>$entities));
			$content = \elgg_view('search/filter');
			$content .= \elgg_list_entities(array('guids'=>$guids, 'full_view'=>false, 'list_type'=>'list'));
		}
		$body = \elgg_view_layout('one_column', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
