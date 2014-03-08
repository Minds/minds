<?php
/**
 * Layout of a river item
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = $vars['item'];

echo elgg_view('river/elements/header', $vars);
echo elgg_view('river/elements/body', $vars);