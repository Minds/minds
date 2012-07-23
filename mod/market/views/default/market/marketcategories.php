<?php
	/**
	 * Elgg Market Plugin
	 * @package market (forked from webgalli's Classifieds Plugin)
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author slyhne
	 * @copyright TechIsUs
	 * @link www.techisus.dk
	 */

	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
		$selected_marketcategory = $vars['entity']->marketcategory;
	}
	$marketcategories = string_to_tag_array(elgg_get_plugin_setting('market_categories', 'market'));

	if (!empty($marketcategories)) {
		if (!is_array($marketcategories)) $marketcategories = array($marketcategories);
		$options = array();
		foreach ($marketcategories as $option) {
			$options[$option] = elgg_echo("market:{$option}");
		}		

		echo "<label>" . elgg_echo('market:categories:choose') . "&nbsp;";
		echo elgg_view('input/dropdown',array(
							'options_values' => $options,
							'value' => $selected_marketcategory,
							'name' => 'marketcategory'
							));
		echo "</label>";

	}

?>
