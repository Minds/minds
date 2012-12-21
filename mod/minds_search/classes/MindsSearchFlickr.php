<?php

class MindsSearchFlickr extends MindsSearch {

	function __construct() {
		$this->name = 'flickr';
		$this->api_key = 'd3b62d6061230abb78e877409efd45b3';
		$this->api_key_secret = '82a01f54068e5768';
		//$this->end_point = 'https://secure.flickr.com/services/rest';
		$this->end_point = 'https://secure.flickr.com/services/rest/?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'api_key='.$this->api_key.'&method=flickr.photos.search&license=cc&text='.$q.'&format=php_serial&per_page='.$limit.'&page='.$page);
		$obj = unserialize($get);
		return $this->renderData($obj['photos']);
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = $data['page'];
		$info->perpage = $data['perpage'];
		$info->total = $data['total'];
		
		foreach($data['photo'] as $photo){
			$item = new stdClass();
			$item->id = $photo['id'];
			$item->title = $photo['title'];
			$item->iconURL = 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_q.jpg';
			$item->href = 'http://www.flickr.com/photos/'.$photo['owner'].'/'.$photo['id'];
			$item->source = 'flickr';
			$rtn[] = $item;
		}
		
		$info->photos = $rtn;
		
		return $info;
	}

}
