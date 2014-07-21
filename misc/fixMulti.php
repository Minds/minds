<?php 
/**
 * Multisite replication factor fix
 */

require_once('/var/www/elgg/vendor/autoload.php');


$sys = new phpcassa\SystemManager('10.0.8.105:9160');

$keyspaces = $sys->describe_keyspaces();

foreach($keyspaces as $keyspace){

	foreach($keyspace as $k){
		$keyspace =$k;
		break;
	}

//	$attrs = array("strategy_options"=>array("replication_factor" => 2));
//	$out = $sys->alter_keyspace($keyspace, $attrs);
	echo "$keyspace \n";
}
