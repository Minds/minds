<?php
/**
 * Upgrade for sprint 11
 */
 
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");


/**
 * Create the counters table.
 */
/*$client = Minds\Core\Data\Client::build('Cassandra');
$query = new Minds\Core\Data\Cassandra\Prepared\System();
$client->request($query->createTable("counters", array("guid"=>"varchar", "metric"=>"varchar", "count"=>"counter"), array("guid", "metric")));
echo "complete \n";*/

$client = Minds\Core\Data\Client::build('Cassandra');
$query = new Minds\Core\Data\Cassandra\Prepared\Counters();
$result = $client->request($query->setQuery("SELECT * from minds.counters"));
var_dump($result);

exit;