<?php
/*
 * http://pixabay.com/api/docs/
 * 
 */

class MindsSearchArchivedotOrg extends MindsSearch {

	function __construct() {
		$this->name = 'archivedotorg';
		$this->end_point = 'http://archive.org/advancedsearch.php?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'q='.$q.'+AND+licenseurl:[http://creativecommons.org/a+TO+http://creativecommons.org/z]&fl[]=identifier,title,mediatype,collection,url&output=json&rows='.$limit.'&page='.$page);
		$obj = json_decode($get);
		return $this->renderData($obj->response);
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = 1;
		$info->perpage = count($data);
		$info->total = $data->numfound;
		foreach($data->docs as $media){
			$item = new stdClass();
			$item->title = $media->title;
			$item->href =  'http://archive.org/details/'.$media->identifier;
			$item->source = 'archive.org';
			if($media->mediatype=='movies'){
				$item->iconURL = elgg_get_site_url().'mod/minds/graphics/icons/Video.png';
				$rtn_videos[] = $item;
			}elseif($media->mediatype=='audio'){
				$item->iconURL = elgg_get_site_url().'mod/minds/graphics/icons/Audio.png';
				$rtn_sounds[] = $item;
			}elseif($media->mediatype=='image'){
				$item->iconURL = elgg_get_site_url().'mod/minds/graphics/icons/JPEG.png';
				$rtn_photos[] = $item;
			}
		}
		$info->videos = $rtn_videos;
		$info->photos = $rtn_photos;
		$info->sounds = $rtn_sounds;
		return $info;
	}

}
