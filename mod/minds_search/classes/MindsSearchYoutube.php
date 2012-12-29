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
		(int) $offset = $limit * $page == 0?1:$limit * $page;
		$get = $this->get($this->end_point.'alt=json&license=cc&v=2&q='.$q.'&max-results='.$limit.'&start-index='.$offset);
		$obj = json_decode($get);
		//var_dump($obj->feed);
		return $this->renderData($obj->feed);
	}
	
	function index(){
		$per_page = 50;
		$page = 1;
		$data = $this->query(null, $per_page, $page);
		$pages = $data->total / $per_page;
	
		$es = new elasticsearch();
		$es->index = 'ext';
			
		while($page < $pages){
			$data = $this->query(null, $per_page, $page);//new data based on page
			foreach($data->videos as $item){
				$es->add($item->type, $item->id, json_encode($item));
			}
			$page++;
			//sleep(4);
		}
		return;
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = 1;
		$info->perpage = $data->{'openSearch$itemsPerPage'}->{'$t'};
		$info->total = $data->{'openSearch$totalResults'}->{'$t'};

		foreach($data->{'entry'} as $video){
			$item = new stdClass();
			$item->id =  'youtube_'.$video->{'media$group'}->{'yt$videoid'}->{'$t'};
			$item->title = $video->{'title'}->{'$t'};//we don't get given titles for some odd reason
			$item->iconURL = $video->{'media$group'}->{'media$thumbnail'}[1]->{'url'};
			$item->description = $video->{'media$group'}->{'media$description'}->{'$t'};
			$item->href =  $video->{'link'}[0]->{'href'};
			$item->source = 'youtube';
			$item->owner = $video->{'author'}[0]->{'name'}->{'$t'};
			$item->license = $this->findLicense($video->{'media$group'}->{'media$license'}->{'$t'});
			$item->type = 'video';
			$rtn[] = $item;
		}
		$info->videos = $rtn;
		
		return $info;
	}

}
