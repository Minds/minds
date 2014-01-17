<?php
/**
 * Cassandra implementation for elgg.
 *
 * Includes the core functionalities.
 *
 * @package Elgg.Core
 * @subpackage Database
 */

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;


global $DB;
$DB = new stdClass(); //make a database class for caching etc

/**
 * Initialise Cassandra  DB connection 
 *
 * @return void
 * @access private
 * 
 * @deprecated for DatabaseCall
 */

function db_init() {
	global $CONFIG, $DB;

	require_once(dirname(dirname(dirname(__FILE__))) . '/vendors/phpcassa/lib/autoload.php');
	$servers = $CONFIG->cassandra->servers;
	
	$pool = new ConnectionPool($CONFIG->cassandra->keyspace, $servers, null, 1);
	
	$DB->pool = $pool;

	$cfs = array(	
			'site', 
			'session',
			'plugin', 
//			'config',
			'object', 
			'user', 
			'user_index_to_guid',
			'widget', 
			'entities_by_time',
			'notification', 
			'annotation', 
			'group', 
			'friends', 
			'friendsof', 
			'timeline',
			'newsfeed',
                        'token'
			);
	
	register_cfs($cfs);
}

/**
 * Insert to cassandra
 * 
 *@deprecated for DatabaseCall::insert
 */
function db_insert($guid = NULL, array $options = array()){
	global $DB;
	
	if(!$guid){
		$guid = new GUID();
		$guid = $guid->generate();
	}
	
	$type = $options['type'] ? $options['type'] : 'object'; // assume its an object if no type is specified
	
	//unset guid
	unset($options['guid']);	
	//unset type
	unset($options['type']);
	
	try{	
		$DB->cfs[$type]->insert($guid, $options);
	} catch(Exception $e){
		echo '<pre>';
		var_dump($e);
		echo '</pre>';
		exit;
	}
	return $guid;
}

/**
 * Get from cassandra
 * 
 * @deprecated for DatabaseCall::get
 */
function db_get(array $options = array()){
	global $DB;
	
	$defaults = array(	'type' => "object",
				'subtype' => "",

				'limit' => 12,
				'offset' => "",
			);

	$options = array_merge($defaults, $options);		

	if($options['limit'] == 0){
		unset($options['limit']);
	}

	$type = $options['type'];
	if(!$type || !array_key_exists($type, $DB->cfs)){
		return;
	}
	//if($type == 'plugin'){ echo '<pre>';var_dump(debug_backtrace());echo '</pre>';exit;}
	//echo "Called $type"; var_dump($options);
	try{
		//1. If guids are passed then return them all. Subtypes and other values don't matter in this case
		if($guids = $options['guids']){
			if(is_array($guids)){
				$rows = $DB->cfs[$type]->multiget($options['guids']);
			}else{
				$rows[] = $DB->cfs[$type]->get($guids);
			}
		} elseif($type == 'plugin'){	
			//do we even have any attrs?
			if($attr = $options['attrs']){
				foreach($options['attrs'] as $k => $v){
				       $index_exps[] = new IndexExpression($k, $v);
				}		               
				$index_clause = new IndexClause($index_exps);				
		
				//check the apc cache ... clean this up... getting a bit messy
				if(is_apc_enabled()){
					$shared_cache = new ElggApcCache('db_get_cache');
					//APC REALLY POOR PERFORMANCE..
					$key = 'plugin:active:'.$attr['active'];
					$cached = $shared_cache->load($key);
					if($cached){
                                                $rows = $cached;
                                        } else {
						$rows = $DB->cfs[$type]->get_indexed_slices($index_clause);
					}
					$shared_cache->save($key, $rows);
				} else {			
					$rows = $DB->cfs[$type]->get_indexed_slices($index_clause);
				}
			} else {	
				$rows = $DB->cfs[$type]->get_range("", "");
			}
		} elseif($type == 'user') {
		       
			foreach($options['attrs'] as $k => $v){
			       $index_exps[] = new IndexExpression($k, $v);
			}

			$index_clause = new IndexClause($index_exps);
			$rows = $DB->cfs[$type]->get_indexed_slices($index_clause);

		} elseif($type == 'friends' || $type == 'friendsof'){
			$row = $DB->cfs[$type]->get($options['owner_guid']);
			$users_guids = array();
			foreach($row as $k => $v){
				if($k != 'type' || $k != 0){
					$user_guids[] = $k;
				}
			}
			$type = 'user';
			if($options['output'] == 'guids'){ 
				return $user_guids;
			} else {
				return db_get(array('type'=>'user', 'guids'=>$user_guids));
			}
		} elseif($type == 'annotation'){
			//remove all annotation_ from strings
			unset($options['limit']);
			unset($options['offset']);
			unset($options['type']);
			unset($options['subtype']);
			foreach($options as $k => $v){
				$name = str_replace('annotation_', '', $k);
				$options[$name] = $v;
				$index_exps[] = new IndexExpression($name, $v);
			}
			$index_clause = new IndexClause($index_exps);
                        $rows = $DB->cfs[$type]->get_indexed_slices($index_clause);
			foreach($rows as $row){
				return true;
			}
		} elseif($type == 'session'){
			return $DB->cfs[$type]->get($options['id']);
		} elseif($type == 'newsfeed'){
			if($ids = $options['ids']){
				$rows = $DB->cfs[$type]->multiget($ids);
			} else  {
				if(!$options){
					return; //no options
				}
				foreach($options as $k => $v){
                	               $index_exps[] = new IndexExpression($k, $v);
                       		}

                        	$index_clause = new IndexClause($index_exps);
                        	$rows = $DB->cfs[$type]->get_indexed_slices($index_clause);		
			}
			return $rows;
		} elseif($type == 'timeline'){
			$slice = new ColumnSlice($options['offset'], "", $options['limit'], true);//set to reversed
			$row = $DB->cfs[$type]->get($options['owner_guid'],$slice);
                        foreach($row as $k => $v){
                                if($k != 'type' || $k != 0){
                                        $item_ids[] = $k;
                                }
                        }
			
			return $item_ids;
		}
	} catch (Exception $e){
		return false;
	}        

	foreach($rows as $k => $row){
		$row['guid'] = $k;
		
		$new_row = new StdClass;
	
		foreach($row as $k=>$v){
			$new_row->$k = $v;
                }

		$entities[] = entity_row_to_elggstar($new_row, $type);
 
	}
	return $entities;
}

/**
 * Performance a remove on the database. Either a column or row
 * 
 *@deprecated for DatabaseCall::removeRow or DatabaseCall::removeAttributes
 */
function db_remove($guid = "", $type = "object", array $options = array()){
	
	global $DB;

	if(empty($options)){
		return $DB->cfs[$type]->remove($guid); 
	} else {
		return $DB->cfs[$type]->remove($guid, $options);
	}
}
//create_cfs('object', array(      'owner_guid'=>'UTF8Type', 'access_id'=>'IntegerType', 'subtype'=>'UTF8Type', 'container_guid'=>'UTF8Type'));
//create_cfs('entities_by_time');
/**
 * Creates a column family. This should be run automatically
 * for each new subtype that is created.
 * 
 * @deprecated for DatabaseCall::createCF
 */
function create_cfs($name, array $indexes = array(), array $attrs = array(), $plugin_id){
	global $CONFIG, $DB;

	$sys = new SystemManager($CONFIG->cassandra->servers[0]);

	$attr = array(	"comparator_type" => "UTF8Type",
			"key_validation_class" => 'UTF8Type',
			"default_validation_class" => 'UTF8Type'
			);


	$sys->create_column_family($CONFIG->cassandra->keyspace, $name, $attr);

	foreach($indexes as $index => $data_type){

		$sys->create_index($CONFIG->cassandra->keyspace, $name, $index, $data_type);

	}

}

/**
 * Register a cfs thats has already been installed by the schema. 
 * These are sent via the plugins start.php files.
 * 
 *@deprecated for DatabaseCall();
 */
function register_cfs($name){
	
	global $DB;
	
	if(is_array($name)){
		
		foreach($name as $n){
			$DB->cfs[$n] = new ColumnFamily($DB->pool, $n);
		}

	} else {

		$DB->cfs[$name] = new ColumnFamily($DB->pool, $name);
	
	}
}
//db_validate_column('plugin', array('active'=> "IntegerType"));
/** 
 * Create a column validation value
 * DATA TYPES: 
 * "BytesType", "LongType", "IntegerType", "Int32Type", "FloatType", "DoubleType", "AsciiType", "UTF8Type"
 * "TimeUUIDType", "LexicalUUIDType", "UUIDType", "DateType"
 */
//db_alter_column('plugin', array('featured' => "IntegerType"));
function db_alter_column($cf, $options){
	global $CONFIG,$DB;
	$sys = new SystemManager($CONFIG->cassandra->servers[0]);

//	$sys->truncate_column_family($CONFIG->cassandra->keyspace, $cf);
//	return;	
	foreach($options as $column => $data_type){
//		var_dump($column, $data_type, $CONFIG->cassandra->keyspace, $cf); exit;
		try{
			$sys->alter_column($CONFIG->cassandra->keyspace, $cf, $column, $data_type);
		} catch(Exception $e){
			echo "<pre>";
			var_dump($e);
			echo "</pre>";
		}
	}
}
//db_create_index('annotation' , array('guid' => 'UTF8Type', 'name'=>'UTF8Type', 'value'=>'UTF8Type','owner_guid'=>'UTF8Type'));
/** 
 * Create a indexed column for a column family
 */
function db_create_index($cf, array $options = array()){
	global $CONFIG,$DB;
        $sys = new SystemManager($CONFIG->cassandra->servers[0]);

	foreach($options as $column => $data_type){
		try{
			$sys->create_index($CONFIG->cassandra->keyspace, $cf, $column, $data_type);
		} catch(Exception $e){
                        echo "<pre>";
                        var_dump($e);
                        echo "</pre>";
                }
	}	
}

//elgg_register_event_handler('init', 'system', 'db_init');
