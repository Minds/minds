<?php
/*
 * http://www.freesound.org/docs/api/overview.html
 * 
 */

class MindsSearchFreesound extends MindsSearch {

	function __construct() {
		$this->name = 'freesound';
		$this->api_key = '529c950cb826468088026e4de280cc23';
		$this->end_point = 'http://www.freesound.org/api/sounds/search?';
	}

	function query($q, $limit=10, $page=1) {
		$q = urlencode($q);
		$get = $this->get($this->end_point.'api_key='.$this->api_key.'&format=json&q='.$q.'&p='.$page.'&sounds_per_page='.$limit.'&fields=id,original_filename,waveform_m,url,preview-hq-mp3,tags,license');
		$obj = json_decode($get);
		return $this->renderData($obj);
	}
	
	function index(){
		$types = array('wav', 'mp3', 'mp3', 'ogg');
		foreach($types as $type){
			$per_page = 100;
			$page = 1;
			$data = $this->query($type, $per_page, $page);
			$pages = $data->total / $per_page;
			
			$es = new elasticsearch();
			$es->index = 'ext';
			
			while($page < $pages){
				$data = $this->query($type, $per_page, $page);//new data based on page
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
		$info->perpage = null;
		$info->total = $data->num_results;

		foreach($data->sounds as $sound){
			$item = new stdClass();
			$item->id = 'freesound_'.$sound->id;
			$item->title = $sound->original_filename;//we don't get given titles for some odd reason
			$item->iconURL = $sound->waveform_m;
			$item->href =  $sound->url;
			$item->preview = $sound->{'preview-hq-mp3'};//we only have a preview for sound types
			$item->source = 'freesound';
			$item->tags = $sound->tags;
			$item->type = 'sound';
			$item->license = $this->findLicense($sound->license);
			$rtn[] = $item;
		}
		$info->sounds = $rtn;
		
		return $info;
	}

}
