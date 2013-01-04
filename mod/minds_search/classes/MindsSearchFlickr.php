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
		$get = $this->get($this->end_point.'api_key='.$this->api_key.'&method=flickr.photos.search&license=1,2,3,4,5,6,7&extras=description,license,owner_name,tags&text='.$q.'&format=php_serial&sort=date-post-asc&per_page='.$limit.'&page='.$page);
		$obj = unserialize($get);
		return $this->renderData($obj['photos']);
	}
	
	function index(){
		$per_page = 500;
		$data = $this->query(null, $per_page, $page);
		$toIndex = $data->total - $this->total('flickr');
		$pages = $data->total / $per_page;
		$page = $this->total('flickr') / $per_page;
		
		$es = new elasticsearch();
		$es->index = 'ext';
		
		while($page < $pages){
			$data = $this->query(null, $per_page, $page);//new data based on page
			foreach($data->photos as $item){
				$es->add($item->type, $item->id, json_encode($item));
			}
			//$page++;
			//sleep(4);
		}
		
		return;
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = $data['page'];
		$info->perpage = $data['perpage'];
		$info->total = $data['total'];
		
		foreach($data['photo'] as $photo){
			$item = new stdClass();
			$item->id = 'flickr_'.$photo['id'];
			$item->title = $photo['title'];
			$item->iconURL = 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_q.jpg';
			$item->description = $photo['description']['content'];
			$item->tags = $photo['tags'];
			$item->owner = $photo['ownername'];
			$item->license = $this->findLicense($photo['license']);
			$item->href = 'http://www.flickr.com/photos/'.$photo['owner'].'/'.$photo['id'];
			$item->source = 'flickr';
			$item->type = 'photo';
			$rtn[] = $item;
		}
		
		$info->photos = $rtn;
		
		return $info;
	}

}
