<?php

class MindsSearchOpenClipart extends MindsSearch {

	function __construct() {
		$this->name = 'openclipart';
		$this->end_point = 'http://openclipart.org/search/json/?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'query='.$q.'&results='.$limit.'&page='.$page);
		$obj = json_decode($get);
		return $this->renderData($obj);
	}
	
	function index(){
		$types = array('public', 'png','gif','jpg');
		foreach($types as $type){
			$per_page = 10;
			$page = 1;
			$data = $this->query($type, $per_page, $page);
			$pages = $data->total / $per_page;
			
			$es = new elasticsearch();
			$es->index = 'ext';
			
			while($page < $pages){
				$data = $this->query($type, $per_page, $page);//new data based on page
				foreach($data->photos as $item){
					$es->add($item->type, 'oc_'.rand(0,1000000), json_encode($item));
				}
				$page++;
				//sleep(4);
			}
		}
		
		return;
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = $data->current_page;
		$info->perpage = $data->results / $data->pages;
		$info->total = $data->info->results;

		foreach($data->payload as $photo){
			$item = new stdClass();
			$item->title = $photo->title;
			$item->iconURL = $photo->svg->png_thumb;
			$item->href =  $photo->detail_link;
			$item->source = 'open clipart';
			$item->license ='publicdomaincco';
			$item->tags = $photo->tags;
			$item->type = 'photo';
			$rtn[] = $item;
		}
		
		$info->photos = $rtn;
		
		return $info;
	}

}
