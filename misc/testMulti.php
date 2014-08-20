<?php

global $DOMAIN;
$DOMAIN = 'www.word.am';

require_once('/var/www/multisite/vendor/autoload.php');

$json = file_get_contents('https://www.minds.io/misc/testMulti.php?12');
$data = json_decode($json, true);
foreach($data as $guid => $row){

	 $entities =  new minds\core\data\call('entities',  'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));
	$entities_by_time =  new minds\core\data\call('entities_by_time',  'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10')); 

	foreach($data as $k => $data){

		 $entities_by_time->insert($k, $data);

	}

exit;

	if(is_array($row)){
		$entities->insert($guid, $row);
		$entities_by_time->insert('object', array($guid=>$guid));
		if(isset($row['subtype']))
			$entities_by_time->insert('object:'.$row['subtype'], array($guid=>$guid));

		if(isset($row['owner_guid'])){
			$entities_by_time->insert('object:'.$row['subtype'].':user:'.$row['owner_guid'], array($guid=>$guid));
			 $entities_by_time->insert('object:user:'.$row['owner_guid'], array($guid=>$guid));
		}
	
		echo "$guid \n";
	}
}

exit;
$column_families = array( 'user_index_to_guid');
foreach($column_families as $cf){


	$new =  new minds\core\data\call('entities_by_time',  'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));
	$entities =  new minds\core\data\call('entities',  'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));

	foreach($new->getRow('object:featured', array('limit'=>1000)) as $featured_id => $guid){
		$row = $entities->getRow($guid);
		var_dump($guid);	
//	if(!$row){
	//		$new->removeAttributes('object:featured', array($featured_id));
	//		echo "could not find $guid \n";

	//	}
	}
//var_dump($new_lookup->getRow('cdavidson'));
exit;
	foreach($new_lookup->get('', 10000) as $k => $lookup){

		
		var_dump($k);

	}

	exit;

/*
	$old_lookup = new minds\core\data\call('user_index_to_guid',  'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.8.183'));
	$new_lookup =  new minds\core\data\call('user_index_to_guid',  'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));

	$old = new minds\core\data\call('entities', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.8.183'));
        $new = new minds\core\data\call('entities', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));	

	foreach($old_lookup->get("", 8000000) as $k=>$v){
		echo $new_lookup->insert($k, $v) ."\n";
		foreach($v as $guid=>$ts){
			$data = $old->getRow($guid);
			if($data)
				echo $new->insert($guid, $data) . "\n";
		}
	}

exit;
*/
/*	$old_index = new minds\core\data\call('entities_by_time', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.8.183'));
	$new_index = new minds\core\data\call('entities_by_time', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));

	foreach($old_index->get("", 8000000) as $k=>$v){
		echo $new_index->insert($k, $v) . "\n";
	}
exit;*/
	
/*	$old = new minds\core\data\call('relationships', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.8.183'));
	$new = new minds\core\data\call('relationships', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));
	$done = array();

	foreach($old->get("", 1000000000) as $k=>$v){
		foreach($v as $guid => $ts){
			if($guid == 302433321919451136){
				$k = explode(':', $k);
				$k = $k[0];
				echo "$k should be in the group \n";
				$new->removeRow($guid);
				$new->insert('302433321919451136:member:inverted', array($k=>$k));
			}
		}
		//echo $new->insert($k, $v) . "\n";
	}
*/
/*
	$old = new minds\core\data\call('timeline', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.8.183'));
        $new = new minds\core\data\call('timeline', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));

	foreach($old->get("", 1000000) as $k=>$v){
		$i++;
		echo $new->insert($k, $v) . "\n";
	}
echo $i;
		
exit;
*/

	 $old = new minds\core\data\call('entities', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.8.183'));
        $new = new minds\core\data\call('entities', 'multisite_a12bcbda2cc7075fe380bdb5b2c1d2ad', array('10.0.9.10'));
   

	foreach($old->get("", 100000) as $k=>$v){
			
	//	var_dump($k,$v);
            	echo $new->insert($k, $v) . "\n";
//		echo '<div class="clear:both;"></div>';
        }
exit;
continue;
exit;
	$index = 'object:wallpost';

	$guids = $old_index->getRow($index, array('limit'=>5000));
	if(!$guids){
		echo 'fail';
		exit;
	}
	$new_index->insert($index, $guids);
	
	$data = $old->getRows($guids);

	foreach($data as $guid => $item){
		//create the entity on the new
		echo "Just moved over $guid \n";
		$new->insert($guid, $item);
	}	
	exit;
}

/*require_once(dirname(dirname(__FILE__)) . '/engine/start.phpe);
global $CONFIG;
//var_dump($CONFIG->cassandra);
//exit;
$db = new minds\core\data\call('entities');
var_dump($db->getRow('279589839555268608'));

//$entities = elgg_get_entities(array('type'=>'object'));

//var_dump($entities);
*/
