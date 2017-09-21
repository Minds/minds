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
use Minds\Entities;

echo "Collecting trending users:: \n";

$prepared = new Core\Data\Neo4j\Prepared\Common();
$result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingUsers(0, 750));
$rows = $result->getRows();

$g = new GUID(); 
$guids = array();
$i = -1;
foreach ($rows['user'] as $user) {
    $user = Entities\Factory::build($user['guid']);

    if ($user->getSpam() || $user->getDeleted()) {
        continue;
    }

    $key = $g->migrate($i++);
    $guids[$key] = $user['guid'];
}

echo count($guids);

$db = new Core\Data\Call('entities_by_time');
$db->insert('trending:user', $guids);

echo "[complete] \n";

$subtypes = ['image', 'video'];

foreach($subtypes as $subtype){

    echo "Collecting trending {$subtype}s:: \n";

    $result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects($subtype, 0, 750));
    $rows = $result->getRows();

    $g = new GUID(); 
    $guids = array();
    $i = -1;
    foreach ($rows['object'] as $object) {
        $entity = Entities\Factory::build($object['guid']);
        $owner = Entities\Factory::build($entity->owner_guid);
        if($entity && $entity->getFlag('mature'))
            continue;
        if($entity && $entity->getFlag('spam'))
            continue;
        if($entity && $entity->getFlag('deleted'))
            continue;
        if($owner && $owner->getMatureContent())
            continue;
        $key = $g->migrate($i++);
        $guids[$key] = $object['guid'];
    }

    echo count($guids);

    $db = new Core\Data\Call('entities_by_time');
    $db->removeRow('trending:' . $subtype);
    $db->insert('trending:' . $subtype, $guids);

    echo "[complete] \n";

}


