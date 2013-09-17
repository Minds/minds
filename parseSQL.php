<?php

require('install/vendors/PHP-SQL-Parser/php-sql-parser.php');
require('engine/start.php');

ini_set('memory_limit', '4G');
set_time_limit ( 0 );
error_reporting(E_ERROR);

$tables = array('objects_entity', 'users_entity', 'entities', 'entity_subtypes', 'metadata', 'metastrings', 'private_settings');

//@todo make this recieve variab;es
$mysql = mysqli_connect("192.168.200.16","minds","","minds") or die("Error " . mysqli_error($link));

$data = new StdClass();

foreach($tables as $table){
	echo "Gathering table: $table... this may take a few minutes \n";
	$query = $mysql->query('SELECT * FROM minds.elgg_'.$table);
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

foreach($data->entities as $row){
	$row->guid = (int)$row->guid;
	$row->owner_guid = (int)$row->owner_guid;
	$row->access_id = (int)$row->access_id;

	if($row->type =='group' || $row->type == 'plugin' ||  $row->subtype == 'oauthnonce' || !$row->type){ continue; }
	$entity = entity_row_to_elggstar($row, $row->type);
	$guid = $entity->save();
	echo "Migrated: {$row->type}:{$row->subtype}:$guid \n";
}

echo "Migration complete... please test!\n";

exit;

if ($script = file_get_contents($dumpfile)) {
	// Remove MySQL -- style comments
	$script = preg_replace('/\-\-.*\n/', '', $script);

	echo "File has been loaded... now parsing \n";
	
	// Statements must end with ; and a newline
		$sql_statements = preg_split('/;[\n\r]+/', $script);
		
		foreach ($sql_statements as $statement) {
			$statement = trim($statement);
			if(strpos($statement,'INSERT') !== false){

				//GET THE METASTRING
				if(strpos($statement,'metastrings` VALUES') !== false){
					$statement = explode('VALUES ',$statement);
					$statement = $statement[1];
					$explode = explode('),',$statement);
					foreach($explode as $item){
						$item = str_replace('(','',$item);
						$item = explode(",'", $item);
						$id = $item[0];
						$data->metastring[$id]['id'] = $id;
						$data->metastring[$id]['value'] = $item[1];
					}
				}

				//get the metadata
                                if(strpos($statement,'metadata` VALUES') !== false){
					$statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement); 
                                	foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                                                $item = explode(",", $item);
                                                $id = $item[0];
						$data->metadata[$id]['id'] = $id;
						$data->metadata[$id]['guid'] = $item[1];
						$data->metadata[$id]['name_id'] = $item[2];
						$data->metadata[$id]['value_id'] = $item[3];
                                        }
					
				}

				//get the users
				if(strpos($statement,'users_entity` VALUES') !== false){
                                        $statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement); 
                                        foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                   				$item = str_replace("'", '', $item);
		                           	$item = explode(",", $item);
                                                $guid = $item[0];
                                                $data->user[$guid]['guid'] = $guid;
                                                $data->user[$guid]['name'] = $item[1];
                        			$data->user[$guid]['username'] = $item[2];
						$data->user[$guid]['password'] = $item[3];
						$data->user[$guid]['salt'] = $item[4];
						$data->user[$guid]['email'] = $item[5];                
						$data->user[$guid]['language'] = $item[6]; 
						$data->user[$guid]['code'] = $item[7]; 
						$data->user[$guid]['banned'] = $item[8]; 
						$data->user[$guid]['admin'] = $item[9]; 
						$data->user[$guid]['last_action'] = $item[10]; 
					}
                                        
                                }

				//get the objects
				if(strpos($statement,'objects_entity` VALUES') !== false){
                                        $statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement); 
                                        foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                                            	$item = str_replace("'", '', $item);
						$item = explode(",", $item);
						var_dump($item); exit;
                                                $guid = $item[0];
                                                $data->object[$guid]['guid'] = $guid;
                                                $data->object[$guid]['title'] = $item[1];
                                                $data->object[$guid]['description'] = $item[2];
				        }
                                } 
				
				//get all entites
				if(strpos($statement,'entities` VALUES') !== false){
                                        $statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement);
                                        foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                                                $item = str_replace("'", '', $item);
						$item = explode(",", $item);
                                                $guid = $item[0];
						$type = $item [1];
						$data->{$type}[$guid]['guid'] = $guid;
                                                $data->{$type}[$guid]['type'] = $type;
                                                $data->{$type}[$guid]['subtype'] = $item[2];
						$data->{$type}[$guid]['owner_guid'] = $item[3];
                                        }

                                }

				//get subtypes
                                if(strpos($statement,'entity_subtypes` VALUES') !== false){
                                        $statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement);
                                        foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                                                $item = str_replace("'", '', $item);
                                                $item = explode(",", $item);
                                                $id = $item[0];
                                                $data->subtypes[$id]['id'] = $id;
                                                $data->subtypes[$id]['type'] = $item[1];
                                                $data->subtypes[$id]['subtype'] = $item[2];
                                        }

                                }	

				if(strpos($statement,'datalists` VALUES') !== false){
					$statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement);
                                        foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                                                $item = str_replace("'", '', $item);
                                                $item = explode(",", $item);
                                                $id = $item[1];
                                                $data->datalist[$id]['name'] = $item[0];
                                                $data->datalist[$id]['id'] = $item[1];
                                        }
				}

				if(strpos($statement,'private_settings` VALUES') !== false){
                                        $statement = explode('VALUES ',$statement);
                                        $statement = $statement[1];
                                        $explode = explode('),',$statement);
                                        foreach($explode as $item){
                                                $item = str_replace('(','',$item);
                                                $item = str_replace("'", '', $item);
                                                $item = explode(",", $item);
                                                $id = $item[0];
                                                $data->private[$id]['id'] = $id;
                                                $data->private[$id]['entity_guid'] = $item[1];
						$data->private[$id]['name'] = $item[2];
						$data->private[$id]['value'] = $item[3];
                                        }
                                }
			}
		}
}

//now we have the data, let merge the metadata with the entities
$types = array('user', 'object');
foreach($data->metadata as $metadata){
	foreach($types as $type){
		$entity = $data->{$type}[$metadata['guid']];
		if(!$entity){ continue; }
		$name = $data->metastring[$metadata['name_id']]['value'];
		$value = $data->metastring[$metadata['value_id']]['value'];
		$data->{$type}[$metadata['guid']][$name] = $value;	
	}
}

//now add private settings to entitites
foreach($data->private as $ps){
        foreach($types as $type){
                $entity = $data->{$type}[$ps['entity_guid']];
                if(!$entity){ continue; }
                $data->{$type}[$ps['entity_guid']][$ps['name']] = $ps['value'];
        }
}

echo "File finished parsing and merging... beginning cassandrsa import \n";

unset($data->metadata);
unset($data->metastring);

//free up a bit of memory

$stats = array(	'users' => count($data->user),
		'objects' => count($data->object)
		);

//Convert arrays to objects
function array_to_object($array){
	$object = new stdClass();
	foreach($array as $k=>$v){
		if(!$k || !$v){ continue; }
		$object->$k = $v;
	}
	return $object;
}

echo "NOW IMPORTING " . $stats['users'] . " USERS...\n";
foreach($data->user as $user){
	$user = new ElggUser(array_to_object($user));
	$user->save();
}

echo "USERS IMPORTED!! \n\n";

echo "NOW IMPORTING " . $stats['objects'] . " OBJECTS...\n";

foreach($data->object as $object){
        $subtype = $data->subtypes[$object['subtype']]['subtype'];
	if($subtype == 'plugin' || $subtype == 'widget'){//remove plugins
                continue;
        }
	$object = new ElggObject(array_to_object($object));
	if(!$object->title){
		echo 'There was an issue with GUID:'. $object->guid;
		continue;
	}
	$object->subtype = $subtype;
	$object->save();
}

echo "All done! Now test and debug \n";
