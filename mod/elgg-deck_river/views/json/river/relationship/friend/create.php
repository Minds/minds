<?php
/**
 * JSON create friend river view
 *
 * @uses $vars['item']
 */

global $jsonexport;

$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();

$subject_icon = elgg_view_entity_icon($subject, 'tiny', array('use_hover' => false));
$object_icon = elgg_view_entity_icon($object, 'tiny', array('use_hover' => false));

$vars['item']->summary = elgg_view('river/elements/summary', array('item' => $vars['item']), FALSE, FALSE, 'default');
$vars['item']->message = '<div style="white-space: normal;">' . $subject_icon . elgg_view_icon('arrow-right') . $object_icon . '</div>';

$jsonexport['results'][] = $vars['item'];