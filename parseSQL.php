<?php

require('engine/start.php');

$GUID = new GUID();

ini_set('memory_limit', '8G');
set_time_limit ( 0 );
error_reporting(E_ALL);

$tables = array('objects_entity', 'groups_entity', 'users_entity', 'entities', 'entity_subtypes', 'entity_relationships', 'metadata', 'metastrings', 'private_settings');

//@todo make this recieve variab;es
$mysql = mysqli_connect("10.0.4.89","minds","Cosmic#revo2012","elgg") or die("Error " . mysqli_error($link));

$data = new StdClass();

foreach($tables as $table){
	echo "Gathering table: $table... this may take a few minutes \n";
	$query = $mysql->query('SELECT * FROM elgg.elgg_'.$table);
	//var_dump($data->$tables);
	while($row = mysqli_fetch_object($query)) {
		//guid or id?
		if($row->guid){
			$data->{$table}[$row->guid] = $row;
		} else {
			$data->{$table}[$row->id] = $row;
		}
	} 
}

echo "Complete! \n\n";

echo "Merging split entity tables \n";
foreach($data->entities as $guid => $entity){
	$table = $entity->type . 's_entity';
	$secondary =  $data->{$table}[$guid];
	if(!$secondary){ unset($data->entities[$guid]); continue; }

	foreach($secondary as $k => $v){
		$entity->$k = utf8_encode($v);
	}

	//fix subtype issue
	$entity->subtype = $data->entity_subtypes[$entity->subtype]->subtype;
	
	//if subtype is from archive, give a super subtype
	if(in_array($entity->subtype, array('kaltura_video','album','image','file'))){
		$entity->super_subtype = 'archive';
	}

	//make widgets into type
        if($entity->subtype == 'widget'){
                $entity->type = 'widget';
                $entity->subtype = '';
        }
	
	$data->entities[$guid] = $entity;
}

echo "Beginning metadata merge... \n";

//merge metadata and metastrings
foreach($data->metadata as $id => $metadata){
	$metadata->name = utf8_encode($data->metastrings[$metadata->name_id]->string);
	$metadata->value = utf8_encode($data->metastrings[$metadata->value_id]->string);
	$data->metadata[$id] = $metadata;

	//append this metadata to the entity
	$data->entities[$metadata->entity_guid]->{$metadata->name} = $metadata->value;
} 

echo "Merging private settings... \n";

foreach($data->private_settings as $id => $ps){
	$data->entities[$ps->entity_guid]->{$ps->name} = $ps->value;	
}

echo "Data merges complete... \n";

echo "Now saving entities to Cassandra... this may take a while \n";

$errors = array();

foreach($data->entities as $row){
        try{
                $guid = $GUID->migrate($row->guid);
                $container_guid = $row->container_guid == "0" ? 0 :  $GUID->migrate($row->container_guid);
		 $row->legacy_guid = $row->guid;
                $row->guid = $guid;
                $row->container_guid = $container_guid;
                $row->owner_guid = $GUID->migrate($row->owner_guid);//we need to move owners too
                $row->access_id = (int)$row->access_id;
                if($row->type =='group' || $row->subtype == 'oauth2_access_token' || $row->subtype == 'plugin' || $row->subtype == 'notification' ||  $row->subtype == 'oauthnonce' || !$row->type){ continue; }
                $entity = entity_row_to_elggstar($row, $row->type);
                $entity->save();
                echo "Migrated: {$row->type}:{$row->subtype}:$guid \n";
        } catch(Exception $e){
                $errors[] = $e->getMessage();
        }
}

echo "\n\n Beginning subscriptions transfer \n";

foreach($data->entity_relationships as $relationship){
	try{
		if($relationship->relationship == 'friend'){
			$user = get_entity( $GUID->migrate($relationship->guid_one), 'user');
			$user2 = get_entity( $GUID->migrate($relationship->guid_two),'user');
			if(!($user instanceof ElggUser) || !($user2 instanceof ElggUser) && (!$user || !$user2)){
				continue;
			}
			$user->addFriend( $GUID->migrate($relationship->guid_two));
		echo "{$user->name} is now following {$user2->name}\n";
		}
	} catch(Exception $e){
		$errors[] = $e->getMessage();
	}
}

echo "Migration complete\n";

if(!empty($errors)){
	$count = count($errors);
	echo "There were $count errors:\n";
	foreach($errors as $error){
		echo $error;
	}
}

