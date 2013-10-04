<?php 

require('engine/start.php');
$GUID = new GUID();

$es_server = '10.0.5.10:9200';
/** 
 * Grab the featured ID's from elasticsearch
 */


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $es_server . '/featured/_search?size=10000');
curl_setopt($ch, CURLOPT_PORT, 9200);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//curl_setopt($ch,CURLOPT_TIMEOUT_MS, 500);
$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);
$data = $data['hits']['hits'];

foreach($data as $row){

	$guid = $GUID->migrate($row['_id']);
	$entity = get_entity($guid, 'object');
	if(!elgg_instanceof($entity, 'object')){
		continue;
	}
	$featured_id = $GUID->generate();
	$subtype =  $row['_type'];
	db_insert('object:featured', array('type'=>'entities_by_time', $featured_id => $guid));
        db_insert('object:'.$subtype.':featured', array('type'=>'entities_by_time',$featured_id => $guid));
	
	try{
	$entity->featured_id = $featured_id;
	$entity->save();
	} catch (Exception $e){

	}
	echo "Featured: $subtype:$guid\n";
}
