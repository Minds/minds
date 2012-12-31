<?php

class MindsSearchArchivedotOrg extends MindsSearch {

	function __construct() {
		$this->name = 'archivedotorg';
		$this->end_point = 'http://archive.org/advancedsearch.php?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'q=licenseurl:[http://creativecommons.org/a+TO+http://creativecommons.org/z]&fl[]=identifier,title,mediatype,collection,url,licenseurl,creator,description&output=json&rows='.$limit.'&page='.$page);
		$obj = json_decode($get);
		return $this->renderData($obj->response);
	}
	
	function index(){
		$per_page =250;
		$page = 1;
		$data = $this->query(null, $per_page, $page);
		$pages = $data->total / $per_page;
	
		$es = new elasticsearch();
		$es->index = 'ext';

		while($page < $pages){
			$data = $this->query(null, $per_page, $page);//new data based on page
			foreach($data->media as $item){
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
		$info->perpage = count($data);
		$info->total = $data->numFound;
		
		foreach($data->docs as $media){
			$item = new stdClass();
			$item->id = 'achorg_'.$media->identifier;
			$item->title = $media->title;
			$item->href =  'http://archive.org/details/'.$media->identifier;
			$item->iconURL = 'http://archive.org/serve/'.$media->identifier.'/format=Thumbnail';
			$item->source = 'archive.org';
			$item->owner = $media->creator;
			$item->description = $media->description;
			$item->license = $this->findLicense($media->licenseurl);
			if($media->mediatype=='movies'){
				$item->type='video';
			}elseif($media->mediatype=='audio'){
				$item->type='sound';
			}elseif($media->mediatype=='image'){
				$item->type='photo';
			}
			$rtn[] = $item;
		}
		$info->media = $rtn;
		return $info;
	}

}
