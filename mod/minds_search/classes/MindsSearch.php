<?php

class MindsSearch {

	function __construct() {
		$this -> server = $server;
	}

	function services() {
		return minds_search_return_services();
	}

	function search($q,$types = array('all'),$services = array('all'), $limit=10, $page=1) {
		$servicesCount = count($this->services());
		$serviceLimit = round($limit / $servicesCount);
		foreach ($this->services() as $service) {
			if(in_array($service->name, $services)){
				$classname = $service->classname;
			} elseif(in_array('all', $services)){
				$classname = $service->classname;
				$limit = $serviceLimit;
			} else{
				continue;//skip if not states or all
			}
			$class = new $classname();
			$data[] = $class->query($q, $limit, $page);
		}
		return $this->mergeData($data);;
		//return $data;
	}
	
	function get($url){
		$rsp = file_get_contents($url);
		return $rsp;
	}
	
	function push($url){
		$data = file_get_contents($url, NULL, stream_context_create(array('http' => 'PUSH')));
		return $data;
	}
	
	function mergeData($data){
		$count = count($data);
		$photos = array();
		$videos = array();
		$sounds = array();
		foreach($data as $k=>$v){
			if($data[$k]->photos){
				$photos = array_merge($photos,$data[$k]->photos);
			}
			if($data[$k]->videos){
				$videos = array_merge($videos,$data[$k]->videos);
			}
			if($data[$k]->sounds){
				$sounds = array_merge($sounds,$data[$k]->sounds);
			}
		}
		$return['photos'] = $photos;
		$return['videos'] = $videos;
		$return['sounds'] = $sounds;
		return $return;
	}
	
	function renderData() {
		if (!is_array($this->data)) {
			$this->data = array();
		}
	}

}
