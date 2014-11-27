<?php
/**
 * Elgg Entity export.
 * Displays an entity as JSON
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = $vars['entity'];

$export = $entity->export();
$export['guid'] = (string) $export['guid'];
$export['url'] = $entity->getURL();

if(elgg_instanceof($entity,'object')){
	$export['ownerObj'] = $entity->ownerObj;
	 $export['ownerObj']['guid'] = (string)  $entity->ownerObj['guid'];
}

//error_log(print_r(debug_backtrace(), TRUE)); 
global $jsonexport;
$jsonexport[$entity->getType()][$entity->getSubtype()][] = $export;
