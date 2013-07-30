<?php
/** 
 * This is a run once schema to populate a basic Minds-Elgg install
 * and setup column family names.
 */

require_once('settings.php');

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

$sys->create_keyspace($CONFIG->cassandra->keyspace);

//column name => ARRAY(	'INDEX' );
$cfs = array(	'site' => array(	'site_id' => 'UUIDType'	),
		'plugin' => array(	'active' => 'IntegerType'	),
		'config' => array(),
		'object' => array(	'guid' => 'UUIDType', 'owner_guid'=>'UUIDType', 'access_id'=>'IntegerType', 'subtype'=>'UTF8Type', 'container_guid'=>'UUIDType'),
		'user' => array(	'guid' => 'UUIDType', 'username' => 'UTF8Type', 'email' => 'UTF8Type'	),
		'group' => array(	'guid' => 'UUIDType', 'container_guid' => 'UUIDType'	),

		'subscribers' => array( 	),
		'subscriptions' => array(	)
	);

foreach($cfs as $cf => $indexes){

	$attr = array("comparator_type" => "UTF8Type");

	$sys->create_column_family($CONFIG->cassandra->keyspace, $cf, $attr);

	foreach($indexes as $index => $data_type){

		$sys->create_index($CONFIG->cassandra->keyspace, $cf, $index, $data_type);
	
	}
}

$pool = new ConnectionPool($CONFIG->cassandra->keyspace, $CONFIG->cassandra->servers);

//autoload site variables
$site = new ColumnFamily($pool, 'site');
$site->insert(1, array(	'guid' => 1, 
			'name' => 'Minds Cassandra',
			'url' => 'http://cassandra.minds.io/',
			'email' => 'mark@minds.com'
		));
