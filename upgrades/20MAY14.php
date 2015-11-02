<?php
/** 
 * This is an update to the schema to support relationships
 * 
 */
require(dirname(dirname(__FILE__)) . '/start.php');
elgg_set_ignore_access();

try {
    $db = new Minds\Core\Data\Call();
    $db->createCF('log');
} catch (Exception $e) {
    var_dump($e);
}
