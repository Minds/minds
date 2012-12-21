<?php
/*
 * http://pixabay.com/api/docs/
 * 
 */

class MindsSearchPixabay extends MindsSearch {

	function __construct() {
		$this->name = 'pixabay';
		$this->username = 'minds';
		$this->api_key = '64056c690cf55682dd65';
		$this->end_point = 'http://pixabay.com/api/?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'username='.$this->username.'&key='.$this->api_key.'&search_term='.$q.'&image_type=photo&per_page='.$limit.'&offset='.$page);
		$obj = json_decode($get);
		return $this->renderData($obj);
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = 1;
		$info->perpage = $data->totalhits;
		$info->total = $data->totalhits;
		
		foreach($data->hits as $photo){
			$item = new stdClass();
			$item->title = null;//we don't get given titles for some odd reason
			$item->iconURL = $photo->previewURL;
			$item->href =  $photo->pageURL;
			$item->source = 'pixabay';
			$rtn[] = $item;
		}
		$info->photos = $rtn;
		
		return $info;
	}

}
