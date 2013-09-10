<?php

class MindsSearch {

	function __construct() {
		$this -> server = $server;
	}

	function services() {
		return minds_search_return_services();
	}
	
	//returns a count of the service
	function total($service){
		$es = new elasticsearch();
		$es->index = 'ext';
		$query = $es->query(null, 'source:'.$service);
		return $query['hits']['total'];
	}
	
	function result($id){
		$es = new elasticsearch();
                $es->index = 'ext';
		return $es->query(null,'id:'.$id);
	}

	function search($q,$type = 'all',$source = array('all'), $license = 'all', $category = 'all', $limit=10,$offset=0) {
		
		$rq = $q;
		if($type == 'all'){
			$type = null;
		}
		if($license != 'all'){
			$rq .= ' AND "'.$license.'"';
		}
		
		if($source == 'all'){
			$source = null;
		} else {
			$rq .= ' AND source:"'.$source.'"';
		}

		if($category != 'all'){	
			$rq .= ' AND category:"'.$category.'"';
		}	

		//I can't get the elasticsearch way to work - so lets do a query string hack!
		if(!$type && !$source)
		$rq .= ' AND type:(user^100,video^2,photo^50,article^1) AND source:(minds^10,wikipedia^40,flickr^30,soundcloud^0.8) OR title:('.$q.'^1) OR description:("'.$q.'"^0.5)';
				
		$data['query']['query_string']['query'] = $rq;
		$data['query']['bool'] = $bool;
		$data['query']['size'] = $limit;
		$data['query']['from'] = $offset;
		
		$es = new elasticsearch();
		$es->index = 'ext';

		return $es->query_data($type, json_encode($data));
	}
	
	function get($url){
		set_time_limit(0);
		$rsp = file_get_contents($url);
		return $rsp;
	}
	
	function push($url){
		$data = file_get_contents($url, NULL, stream_context_create(array('http' => 'PUSH')));
		return $data;
	}
	
	function mergeData($data){
		$count = count($data);
		$photos = array();
		$videos = array();
		$sounds = array();
		foreach($data as $k=>$v){
			if($data[$k]->photos){
				$photos = array_merge($photos,$data[$k]->photos);
			}
			if($data[$k]->videos){
				$videos = array_merge($videos,$data[$k]->videos);
			}
			if($data[$k]->sounds){
				$sounds = array_merge($sounds,$data[$k]->sounds);
			}
		}
		$return['photos'] = $photos;
		$return['videos'] = $videos;
		$return['sounds'] = $sounds;
		return $return;
	}
	
	
	function findLicense($code){
		$cc_by = 'attribution-cc';
		$cc_by_sa = 'attribution-sharealike-cc';
		$cc_by_nd = 'attribution-noderivs-cc';
		$cc_by_nc = 'attribution-noncommerical-cc';
		$cc_by_nc_sa = 'attribution-noncommercial-sharealike-cc';
		$cc_by_nc_nd = 'attribution-noncommercial-noderivs-cc';
		$cc0 = 'publicdomaincco';
		
		switch($code){
			//flickr
			case 1:
				return $cc_by_nc_sa;
				break;
			case 2:
				return $cc_by_nc;
				break;
			case 3:
				return $cc_by_nc_nd;
				break;
			case 4:
				return $cc_by;
				break;
			case 5:
				return $cc_by_sa;
				break;
			case 6:
				return $cc_by_nd;
				break;
			case 7:
				return $cc0;
				break;
			//ccmixter
			case 'Attribution (3.0)':
				return $cc_by;
				break;
			case 'Attribution Noncommercial  (3.0)':
				return $cc_by_nc;
				break;
			case 'Attribution Noncommercial Share-Alike  (3.0)':
				return $cc_by_nc_sa;
				break;
			case 'Attribution Share-Alike  (3.0)':
				return $cc_by_sa;
				break;
			case 'CC0 (CC Zero)':
				return $cc0;
				break;
			//soundcloud
			case 'cc-by':
				return $cc_by;
				break;
			case 'cc-by-sa':
				return $cc_by_sa;
				break;
			case 'cc-by-nd':
				return $cc_by_nd;
				break;
			case 'cc-by-nc':
				return $cc_by_nc;
				break;
			case 'cc-by-nc-sa':
				return $cc_by_nc_sa;
				break;
			case 'cc-by-nc-nd':
				return $cc_by_nc_nd;
				break; 	
			//youtube
			case 'cc':
				return $cc_by;
				break;
			//archive dot org
			//BY
			case 'http://creativecommons.org/licenses/by/2.0/':
				return $cc_by;
				break;
			case 'http://creativecommons.org/licenses/by/2.5/':
				return $cc_by;
				break;
			case 'http://creativecommons.org/licenses/by/3.0/':
				return $cc_by;
				break;
			//SA
			case 'http://creativecommons.org/licenses/by-sa/2.0/':
				return $cc_by_sa;
				break;
			case 'http://creativecommons.org/licenses/by-sa/2.5/':
				return $cc_by_sa;
				break;
			case 'http://creativecommons.org/licenses/by-sa/3.0/':
				return $cc_by_sa;
				break;
			//by-nd
			case 'http://creativecommons.org/licenses/by-nd/2.0/':
				return $cc_by_nd;
				break;
			case 'http://creativecommons.org/licenses/by-nd/2.5/':
				return $cc_by_nd;
				break;
			case 'http://creativecommons.org/licenses/by-nd/3.0/':
				return $cc_by_nd;
				break;
			//by-nc
			case 'http://creativecommons.org/licenses/by-nc/2.0/':
				return $cc_by_nc;
				break;
			case 'http://creativecommons.org/licenses/by-nc/2.5/':
				return $cc_by_nc;
				break;
			case 'http://creativecommons.org/licenses/by-nc/3.0/':
				return $cc_by_nc;
				break;
			//by-nc-sa
			case 'http://creativecommons.org/licenses/by-nc-sa/2.0/':
				return $cc_by_nc_sa;
				break;
			case 'http://creativecommons.org/licenses/by-nc-sa/2.5/':
				return $cc_by_nc_sa;
				break;
			case 'http://creativecommons.org/licenses/by-nc-sa/3.0/':
				return $cc_by_nc_sa;
				break;
			//by-nc-nd
			case 'http://creativecommons.org/licenses/by-nc-nd/2.0/':
				return $cc_by_nc_nd;
				break;
			case 'http://creativecommons.org/licenses/by-nc-nd/2.5/':
				return $cc_by_nc_nd;
				break;
			case 'http://creativecommons.org/licenses/by-nc-nd/3.0/':
				return $cc_by_nc_nd;
				break;
			case 'http://creativecommons.org/licenses/publicdomain/':
				return $cc0;
				break;
		}
	}
	
	function renderData() {
		if (!is_array($this->data)) {
			$this->data = array();
		}
	}

}
