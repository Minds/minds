<?php
/*
 * http://developers.soundcloud.com/docs/api/reference
 * 
 */

class MindsSearchCCMixter extends MindsSearch {

	function __construct() {
		$this->name = 'ccmixter';
		$this->end_point = 'http://ccmixter.org/api/';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'query?format=json&search='.$q.'&limit='.$limit.'&offset='.$limit * ($page-1));
		$obj = json_decode($get);
		return $this->renderData($obj);
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = 1;
		$info->perpage = count($data);
		$info->total = count($data);

		foreach($data as $sound){
			$item = new stdClass();
			$item->id = $sound->upload_id;
			$item->title = $sound->upload_name;
			$item->iconURL = elgg_get_site_url().'mod/minds/graphics/icons/Audio.png';
			$item->href =  'http://ccmixter.org/files/mindmapthat/40507/'.$sound->upload_id;
			$item->source = 'CCMixter';
			$rtn[] = $item;
		}
		$info->sounds = $rtn;
		
		return $info;
	}

}
