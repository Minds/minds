<?php
/**
 * Elgg radio input
 * Displays a radio input field
 
 * @package Elgg Membership
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgg.in/
 */

$class = $vars['class'];
if (!$class) {
	$class = "input-radio";
}

foreach($vars['options'] as $label => $option) {
	if (strtolower($option) != strtolower($vars['value'])) {
		$selected = "";
	} else {
		$selected = "checked = \"checked\"";
	}
	$labelint = (int) $label;
	if ("{$label}" == "{$labelint}") {
		$label = $option;
	}

	if (isset($vars['internalid'])) {
		$id = "id=\"{$vars['internalid']}\"";
	}
	if ($vars['disabled']) {
		$disabled = ' disabled="yes" ';
	}
	echo "<label><input type=\"radio\" $disabled {$vars['js']} name=\"{$vars['name']}\" $id value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\" {$selected} class=\"$class\" />{$label}</label>&nbsp;";
}