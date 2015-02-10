<?php
/**
 * Upgrade for sprint 11
 */
 
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$client = Minds\Core\Data\Client::build('Cassandra');
$query = new Minds\Core\Data\Cassandra\Prepared\System();
$client->request($query->alterTableAddColumn("entities", "counter", "counter"));