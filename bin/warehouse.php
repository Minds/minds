<?php
/**
 * Data warehouse
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

//@todo listen for arguments here

/**
 * Commence Neo4j population.
 */
$start = microtime();
\Minds\Core\Data\Warehouse\Factory::build('Neo4j')->run(array('sync', isset($argv[1]) ? $argv[1] : NULL));
$end = microtime();

$total = $end-$start;
echo "\n\n TOOK: $total \n\n";
