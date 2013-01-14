<?php

// http://www.elasticsearch.com/docs/elasticsearch/rest_api/

class elasticsearch {
  public $index;

  function __construct($server = elasticsearch_server){
    $this->server = $server;
  }

  function call($path, $http = array('method'=>'GET')){
    if (!$this->index) throw new Exception('$this->index needs a value');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, '107.23.117.9/'. $this->index . '/' . $path);
	curl_setopt($ch, CURLOPT_PORT, 9200);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http['method']);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS, 25);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $http['content']);
	$result = curl_exec($ch);
	curl_close($ch);
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
    return $this->call($type . '/' . $id, array('method' => 'PUT', 'content' => $data));
  }
  
  //curl -X DELETE http://localhost:9200/{INDEX}/{TYPE}/{ID}
  function remove($type, $id){
    return $this->call($type . '/' . $id, array('method' => 'DELETE'));
  }

  //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
  function query($type, $q, $sort, $size = 25, $from = 0){
    return $this->call($type . '/_search?' . http_build_query(array('q' => $q, 'sort'=>$sort,'size'=> $size, 'from'=> $from)));
  }
}
