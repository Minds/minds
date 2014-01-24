<?php
/** 
 * This is an update to the schema to support relationships
 * 
 */
require(dirname(__FILE__) . '/start.php');

$db = new DatabaseCall();
$db->createCF('relationships');

