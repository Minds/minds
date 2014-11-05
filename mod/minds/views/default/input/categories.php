<?php
/**
 * Categories input view
 *
 * @package ElggCategories
 *
 * @uses $vars['entity'] The entity being edited or created
 */

if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
	$selected_categories = $vars['entity']->category;
}

global $CONFIG;
$categories = $CONFIG->site_categories ?: elgg_get_site_entity()->categories;


if (empty($categories)) {
	$categories = array();
}
if (empty($selected_categories)) {
	$selected_categories = '';
}

if (!empty($categories)) {
	if (!is_array($categories)) {
		$categories = explode(',', $categories);
		foreach($categories as $k => $v)
			$categories[$k] = trim($v);
	}

	// checkboxes want Label => value, so in our case we need category => category
	$categories = array_flip($categories);
	array_walk($categories, create_function('&$v, $k', '$v = $k;'));

	?>

<div class="categories">
	<label><?php echo elgg_echo('categories'); ?></label>
	<?php
		echo elgg_view('input/dropdown', array(
			'options' => $categories,
			'value' => $selected_categories,
			'name' => 'universal_categories_list',
		));

	?>
	<input type="hidden" name="universal_category_marker" value="on" />
</div>

	<?php

} else {
	echo '<input type="hidden" name="universal_category_marker" value="on" />';
}
