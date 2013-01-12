<?php
$id = get_input('id');
$type = get_input('type');
if(!$id){
	return false;
}

$mc = new MindsComments();
$mc->delete($type,$id);

