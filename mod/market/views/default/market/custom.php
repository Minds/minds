<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
	$custom_selected = $vars['entity']->custom;
}

$custom_choices = string_to_tag_array(elgg_get_plugin_setting('market_custom_choices', 'market'));
if (empty($custom_choices)) $custom_choices = array();
if (empty($custom_selected)) $custom_selected = array(); 

if (!empty($custom_choices)) {
	if (!is_array($custom_choices)) $custom_choices = array($custom_choices);

	echo "<label>" . elgg_echo('market:custom:select') . "&nbsp;";
	echo elgg_view('market/input/pulldown',array(
						'options' => $custom_choices,
						'value' => $custom_selected,
						'name' => 'marketcustom'
						));
	echo "</label>";

}

