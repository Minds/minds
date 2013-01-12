<?php

class MindsComments {

	function __construct() {
		$this->index = 'comments';
	}

	function services() {
		return minds_search_return_services();
	}
	
	//returns a count of the comments
	function total($type, $pid){
		$es = new elasticsearch();
		$es->index = $this->index;
		$query = $es->query($type, 'pid:'.$service);
		return $query['hits']['total'];
	}
	
	function output($type, $pid, $limit= 10, $offset=0){
		$es = new elasticsearch();
		$es->index = $this->index;
		$comments = $es->query($type, 'pid:'.$pid, 'time_created:desc', $limit, $offset);
		return $comments;
	}

	function create($type, $pid, $comment) {
		$es = new elasticsearch();
		$es->index = $this->index;
		$data = new stdClass();
		$data->description = $comment;
		$data->pid = $pid;
		$data->owner_guid = elgg_get_logged_in_user_guid();
		$data->time_created = time();
		
		$id = $data->time_created . $data->owner_guid;
		return $es->add($type,$id,json_encode($data));
	}
	
	function delete($type, $id){
		$es = new elasticsearch();
		$es->index = $this->index;
		return $es->remove($type, $id);
	}
}
