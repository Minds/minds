<?php
/*
 * http://pixabay.com/api/docs/
 * 
 */

class MindsSearchYoutube extends MindsSearch {

	function __construct() {
		$this->name = 'youtube';
		$this->end_point = 'https://gdata.youtube.com/feeds/api/videos/?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		(int) $offset = $limit / $page == 0?1:$page;
		$get = $this->get($this->end_point.'alt=json&license=cc&v=2&q='.$q.'&max-results='.$limit.'&start-index='.$offset);
		$obj = json_decode($get);
		//var_dump($obj->feed);
		return $this->renderData($obj->feed);
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = 1;
		$info->perpage = $data->{'openSearch$itemsPerPage'}->{'$t'};
		$info->total = $data->{'openSearch$totalResults'}->{'$t'};

		foreach($data->{'entry'} as $video){
			$item = new stdClass();
			$item->title = $video->{'title'}->{'$t'};//we don't get given titles for some odd reason
			$item->iconURL = $video->{'media$group'}->{'media$thumbnail'}[1]->{'url'};
			$item->href =  $video->{'link'}[0]->{'href'};
			$item->source = 'youtube';
			$rtn[] = $item;
		}
		$info->videos = $rtn;
		
		return $info;
	}

}
