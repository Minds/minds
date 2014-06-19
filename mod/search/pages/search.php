<?php
/**
 * Search page controller
 */
namespace minds\plugin\search\pages;

use minds\core;
use minds\interfaces;
use minds\entities;
use minds\plugin\search\services\elasticsearch;

class search extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		$client = new \Elasticsearch\Client(array('hosts'=>array(\elgg_get_plugin_setting('server_addr','search'))));
		$params = array();
		
		$query = $_GET['q'];
	
		if(!$query){
			exit;
		}
		$category = \get_input('category');
		if($category)
			$query = "query AND $category";

		$body['query']['query_string']['query'] = $query;
		$body['query']['query_string']['fields'] = array('_all', 'name^5', 'title^8');
		
		$params['index'] = 'minds';
		$params['size'] = \get_input('limit');
		$params['from'] = \get_input('offset');
		$params['body']  = $body;
		$results = $client->search($params);
		$guids = array();
		foreach($results['hits']['hits'] as $raw){
			array_push($guids, $raw['_id']);
		}

		if(empty($guids)){
			$content = 'Sorry, no results could be found';
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
