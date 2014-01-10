<?php
/** 
 * This is a run once schema to populate a basic Minds-Elgg install
 * and setup column family names.
 */

require_once('settings.php'); global $CONFIG;

require(dirname(dirname(__FILE__)) . '/vendors/phpcassa/lib/autoload.php');

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;


$sys = new SystemManager($CONFIG->cassandra->servers[0]);

//$sys->create_keyspace($CONFIG->cassandra->keyspace);

//column name => ARRAY(	'INDEX' );
$cfs = array(	    
                'token' => array('owner_guid'=>'UTF8Type', 'expires' =>'IntegerType' )
	);

foreach($cfs as $cf => $indexes){

	$attr = array("comparator_type" => "UTF8Type");

	$sys->create_column_family($CONFIG->cassandra->keyspace, $cf, $attr);

	foreach($indexes as $index => $data_type){

		$sys->create_index($CONFIG->cassandra->keyspace, $cf, $index, $data_type);
		echo 'created '. $cf;	
	}
}

