<?php
/*
 * http://pixabay.com/api/docs/
 * 
 * NOTE: This service may have duplicates. Needs attention.
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
		$get = $this->get($this->end_point.'username='.$this->username.'&key='.$this->api_key.'&image_type=photo&per_page='.$limit.'&offset='.$page);
		$obj = json_decode($get);
		return $this->renderData($obj);
	}
	
	function index(){
		$per_page = 100;
		$page = 1;
		$data = $this->query(null, $per_page, $page);
		$pages = 2000 / $per_page;
		
		$es = new elasticsearch();
		$es->index = 'ext';
		
		while($page < $pages){
			$data = $this->query(null, $per_page, $page);//new data based on page
			$id = 0;
			foreach($data->photos as $item){
				$es->add($item->type, 'pixabay_'.$page.$id, json_encode($item));
				$id++;
			}
			$page++;
		}
		
		return;
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
			$item->type = 'photo';
			$item->license = 'publicdomaincco';
			$rtn[] = $item;
		}
		$info->photos = $rtn;
		
		return $info;
	}

}
