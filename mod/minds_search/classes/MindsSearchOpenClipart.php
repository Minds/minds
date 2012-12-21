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
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = $data->current_page;
		$info->perpage = $data->results / $data->pages;
		$info->total = $data->results;
		
		foreach($data->payload as $photo){
			$item = new stdClass();
			$item->title = $photo->title;
			$item->iconURL = $photo->svg->png_thumb;
			$item->href =  $photo->detail_link;
			$item->source = 'open clipart';
			$rtn[] = $item;
		}
		
		$info->photos = $rtn;
		
		return $info;
	}

}
