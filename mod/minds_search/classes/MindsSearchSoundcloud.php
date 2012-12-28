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

	function query($license, $limit=10, $page=1) {
		$get = $this->get($this->end_point.'tracks.json?client_id='.$this->client_id.'&license='.$license.'&limit='.$limit.'&offset='.$limit * ($page-1));
		$obj = json_decode($get);
		return $this->renderData($obj);
	}
	
	function index(){
		$licenses = array('cc-by', 'cc-by-sa', 'cc-by-nd', 'cc-by-nc', 'cc-by-nc-sa', 'cc-by-nc-nd');
		foreach($licenses as $license){
			$per_page = 100;
			$page = 1;
			$data = $this->query($license, $per_page, $page);
			$pages = 3;
			
			$es = new elasticsearch();
			$es->index = 'ext';
			
			while($page < $pages){
				$data = $this->query($license, $per_page, $page);//new data based on page
				foreach($data->sounds as $item){
					$es->add($item->type, $item->id, json_encode($item));
				}
				$page++;
				//sleep(4);
			}
		}
		return;
	}
	
	function renderData($data){
		parent::renderData();
				
		$info = new stdClass();
		$info->page = 1;
		$info->perpage = count($data);
		$info->total = count($data);

		foreach($data as $sound){
			$item = new stdClass();
			$item->id = 'sc'.$sound->id;
			$item->title = $sound->title;//we don't get given titles for some odd reason
			$item->iconURL = $sound->artwork_url ? $sound->artwork_url : $sound->waveform_url;
			$item->description = $sound->description;
			$item->href =  $sound->permalink_url;
			$item->source = 'soundcloud';
			$item->tags = $sound->tag_list;
			$item->owner = $sound->user->username;
			$item->license = $this->findLicense($sound->license);
			$item->type = 'sound';
			$rtn[] = $item;
		}
		$info->sounds = $rtn;
		
		return $info;
	}

}
