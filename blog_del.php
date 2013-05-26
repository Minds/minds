<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(__FILE__) . "/engine/start.php");

$entities = elgg_get_entities(array('types'=>array('object'), 'subtypes'=>array('blog'), 'limit'=>2000));

foreach($entities as $entity){
	echo $entity->guid;
	if($entity->time_created > time() - 720){
		echo 'attempting to delete';
		$entity->delete();
	}
	$entity->delete();
}
