<?php

use minds\plugin\search\services\elasticsearch as elasticsearch;

class MindsComments {

	function __construct() {
		global $CONFIG;
		$this->index = $CONFIG->elasticsearch_prefix . 'comments';
	}

	function services() {
		return minds_search_return_services();
	}
	
	//returns a count of the comments
	function total($type, $pid){
		$es = new elasticsearch();
		$es->index = $this->index;
		$query = $es->query($type, 'pid:'.$service, null, 0,0, array('age'=>150, 'id'=>'comments.total.'.$type.'.'.$pid));
		return $query['hits']['total'];
	}
	
	function output($type, $pid, $limit= 10, $offset=0){
		$es = new elasticsearch();
		$es->index = $this->index; 
		if($limit == 3){
			//only use cache for the initial comments
			$cache = array('age'=>150, 'id'=>'comments.'.$type.'.'.$pid);
		}
		$comments = $es->query($type, 'pid:"'.$pid . '"', 'time_created:desc', $limit, $offset, $cache);
		return $comments;
	}
	
	function single($type,$id){
		$es = new elasticsearch();
		$es->index = $this->index;
		$comment = $es->call($type.'/'.$id, array('method' => 'GET'));
		return $comment;
	}

	function create($type, $pid, $comment, $owner_guid = NULL) {
		$es = new elasticsearch();
		$es->index = $this->index;
		$data = new stdClass();
		$data->description = $comment;
		$data->pid = $pid;
		$data->owner_guid = $owner_guid ? $owner_guid :  elgg_get_logged_in_user_guid();
		$data->time_created = time();
		
		$id = $data->time_created . $data->owner_guid;
		return $es->add($type,$id,json_encode($data));
	}
	
	function update($type, $id, $source){
		$es = new elasticsearch();
		$es->index = $this->index;
		return $es->add($type,$id,json_encode($source));
	}
	
	function delete($type, $id){
		$es = new elasticsearch();
		$es->index = $this->index;
		return $es->remove($type, $id);
	}
}
