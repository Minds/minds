<?php

// http://www.elasticsearch.com/docs/elasticsearch/rest_api/

class elasticsearch {
  public $index;

  function __construct(){
  	global $CONFIG;
	    $this->server = $CONFIG->elasticsearch_server;
	$this->port = $CONFIG->elasticsearch_port ? $CONFIG->elasticsearch_port : 9200;
	$this->cache_path = '/tmp/es/';
  }
  
  function cache($id, $age = 0){
  	$CACHE = new ElggFileCache($this->cache_path . $id . '/', $age);
	return $CACHE;
  }
  
  function purgeCache($id){
  	$cache = $this->cache($id);
	return $cache->clear();
  }
  
  function getCache($id, $age){
  	$cache = $this->cache($id, $age);
	return $cache->load($id);
  }
  
  function saveCache($id, $data, $age){
	$cache = $this->cache($id, $age);
	return $cache->save($id, $data);
  }

  function call($path, $http = array('method'=>'GET'), $cache = array('age'=>0)){ 
    if (!$this->index) throw new Exception('$this->index needs a value');
		
	if($http['method']=='GET' && $cache['age'] > 0){
		$cache_data = $this->getCache($cache['id'], $cache['age']);
		if($cache_data){
			//echo 'using the cache!! wooo!!';
			return json_decode($cache_data, true);
		}
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $this->server . $this->index . '/' . $path);
	curl_setopt($ch, CURLOPT_PORT, $this->port);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http['method']);
	//curl_setopt($ch,CURLOPT_TIMEOUT_MS, 500);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $http['content']);
	$result = curl_exec($ch);
	curl_close($ch);
	if($cache['age'] > 0){
		$this->saveCache($cache['id'], $result, $cache['age']);
	}
	return json_decode($result,true);
  }

  //curl -X PUT http://localhost:9200/{INDEX}/
  function create(){
     $this->call(NULL, array('method' => 'PUT'));
  }

  //curl -X DELETE http://localhost:9200/{INDEX}/
  function drop(){
     $this->call(NULL, array('method' => 'DELETE'));
  }

  //curl -X GET http://localhost:9200/{INDEX}/_status
  function status(){
    return $this->call('_status');
  }

  //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_count -d {matchAll:{}}
  function count($type){
    return $this->call($type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
  }

  //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_mapping -d ...
  function map($type, $data){
    return $this->call($type . '/_mapping', array('method' => 'PUT', 'content' => $data));
  }

  //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/{ID} -d ...
  function add($type, $id, $data){
	global $CONFIG;
	if($CONFIG->elgg_multisite_settings && $this->index == 'ext'){
		$this->index = $CONFIG->elasticsearch_prefix;
	}
    return $this->call($type . '/'. $id, array('method' => 'PUT', 'content' => $data));
  }
  
  //curl -X DELETE http://localhost:9200/{INDEX}/{TYPE}/{ID}
  function remove($type, $id){
    return $this->call($type . '/' . $id, array('method' => 'DELETE'));
  }

  //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
  function query($type = null, $q =null, $sort='', $size = 25, $from = 0, $cache = array('age'=>0)){
  	if(!isset($cache['id'])){
  		$cache['id'] = urlencode($type.$q);
  	}
    return $this->call($type . '/_search?' . http_build_query(array('q' => $q, 'sort'=>$sort,'size'=> $size, 'from'=> $from)), array('method'=>'GET'), $cache);
  }
  
  function query_data($type = null, $data, $size = 25, $from =0){
  	return $this->call($type . '/_search', array('method'=>'POST', 'content'=>$data));
  }
  
  function terms($type, $data){
  	return $this->call($type . '/_search', array('method'=>'POST', 'content'=>$data));
  }
}
