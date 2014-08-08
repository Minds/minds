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
$export['url'] = $entity->getURL();

if(elgg_instanceof($entity,'object')){
	$export['ownerObj'] = $entity->ownerObj;
}

global $jsonexport;
$jsonexport[$entity->getType()][$entity->getSubtype()][] = $export;