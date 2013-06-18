<?php
/**
 * Polls river view.
 */

$object = $vars['item']->getObjectEntity();
$vars['entity']=$object;
echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message'=> elgg_view('polls/body',$vars)
));
