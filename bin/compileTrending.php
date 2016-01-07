<?php
/**
 * Data warehouse
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
error_reporting(E_ALL);

use Minds\Core;

echo "Collecting trending users:: \n";

$prepared = new Core\Data\Neo4j\Prepared\Common();
$result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingUsers(0, 500));
$rows = $result->getRows();

$g = new GUID(); 
$guids = array();
foreach ($rows['user'] as $i => $user) {
    $i = $g->migrate($i);
    $guids[$i] = $user['guid'];
}

echo count($guids);

$db = new Core\Data\Call('entities_by_time');
$db->insert('trending:user', $guids);

echo "[complete] \n";

$subtypes = ['image', 'video'];

foreach($subtypes as $subtype){

    echo "Collecting trending {$subtype}s:: \n";

    $result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects($subtype, 0, 500));
    $rows = $result->getRows();

    $g = new GUID(); 
    $guids = array();
    foreach ($rows['object'] as $i => $object) {
        $i = $g->migrate($i);
        $guids[$i] = $object['guid'];
    }

    echo count($guids);

    $db = new Core\Data\Call('entities_by_time');
    $db->insert('trending:' . $subtype, $guids);

    echo "[complete] \n";

}


