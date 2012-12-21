<?php
/*
 * http://developers.soundcloud.com/docs/api/reference
 * 
 */

class MindsSearchSoundcloud extends MindsSearch {

	function __construct() {
		$this->name = 'soundcloud';
		$this->client_id = '76c1334ef21c70d72dc89661e638258f';
		$this->client_id_secret = 'd106ca7c52a5f21e82df476f770784a4';
		$this->end_point = 'https://api.soundcloud.com/';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'tracks.json?client_id='.$this->client_id.'&license=cc-by&q='.$q.'&limit='.$limit.'&offset='.$limit * ($page-1));
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
			$item->id = $sound->id;
			$item->title = $sound->title;//we don't get given titles for some odd reason
			$item->iconURL = $sound->artwork_url ? $sound->artwork_url : $sound->waveform_url;
			$item->href =  $sound->permalink_url;
			$item->source = 'soundcloud';
			$rtn[] = $item;
		}
		$info->sounds = $rtn;
		
		return $info;
	}

}
