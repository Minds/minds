<?php 

require('engine/start.php');
$GUID = new GUID();

$es_server = '10.0.5.10:9200';
/** 
 * Grab the featured ID's from elasticsearch
 */


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $es_server . '/news/_search?size=100000');
curl_setopt($ch, CURLOPT_PORT, 9200);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//curl_setopt($ch,CURLOPT_TIMEOUT_MS, 500);
$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);
$data = $data['hits']['hits'];

foreach($data as $row){
	
	$action_type = $row['_type'];
	
	$view = $row['_source']['view'];
	
	$object_guid = $row['_source']['object_guid'];
	$subject_guid = $row['_source']['subject_guid'];
	if($object_guid < 1){
		continue;
	} 
	if($subject_guid < 1){
		continue;
	}

	$object_guid = $GUID->migrate($object_guid);
	$type = $row['_source']['type'];
	$subject_guid = $GUID->migrate($subject_guid);
	$posted = $row['_source']['posted'];

	
	if($type == 'object'){
		

		try{
			add_to_river($view, $action_type, $subject_guid, $object_guid, $access_id = 2,$posted);
		}catch(Exception $e){
			echo "issue with $view:$object_guid";
		}
		echo "Migrated: $object";

	}
}

