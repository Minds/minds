<?php 

// show all the errors
error_reporting(E_ALL);

// the only file that needs including into your project
require_once 'vendors/cassandra-php/Cassandra.php';

// list of seed servers to randomly connect to
// all the parameters are optional and default to given values
$servers = array(
    array(
        'host' => '192.168.200.18',
        'port' => 9160,
        'use-framed-transport' => true,
        'send-timeout-ms' => 1000,
        'receive-timeout-ms' => 1000
    )
);

// create a named singleton, the second parameter name defaults to "main"
// you can have several named singletons with different server pools
$cassandra = Cassandra::createInstance($servers);

//Try to use existing keyspace, if it doesn't exist then create a new one
try { 
	$cassandra->useKeyspace('MindsTest');
} catch(Exception $e){
	//create keyspace
	$cassandra->createKeyspace('MindsTest');

	//now try to use the keyspace again!
	$cassandra->useKeyspace('MindsTest');

	// create a standard column family with given column metadata
	$cassandra->createStandardColumnFamily(
		'MindsTest', // keyspace name
		'user',             // the column-family name
		array(              // list of columns with metadata
			array(
				'name' => 'name',
				'type' => Cassandra::TYPE_UTF8,
				'index-type' => Cassandra::INDEX_KEYS, // create secondary index
				'index-name' => 'NameIdx'
			),
			array(
				'name' => 'email',
				'type' => Cassandra::TYPE_UTF8
			),
			array(
				'name' => 'age',
				'type' => Cassandra::TYPE_INTEGER,
				'index-type' => Cassandra::INDEX_KEYS,
				'index-name' => 'AgeIdx'
			)
		)
		// actually accepts more parameters with reasonable defaults
	);

	$cassandra->set(
		'user.mark',
		array(
			'email' => 'mark@minds.com',
			'name' => 'Mark Harding',
			'age' => 19
		)
	);
}

$cassandra->set(
                'user.xyts',
                array(
                        'email' => 'mark2@minds.com',
                        'name' => 'Kram Harding',
                        'age' => 21
                )
        );


//get the user mark
$mark = $cassandra->get('user.mark');
echo 'User "mark": <pre>'.print_r($mark, true).'</pre><hr/>';

var_dump(Cassandra::OP_LT);
//list of users
try{
	var_dump($cassandra->cf('user')->getWhere(array(array('age',Cassandra::OP_LT,21)))->getAll());
} catch(Exception $e){
	echo '<pre>';
	var_dump($e);
	echo '</pre>';
}
