<?php


elgg_push_context('custom_index_widgets');
elgg_set_page_owner_guid(elgg_get_config('site_guid'));

$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_widgets = elgg_extract('show_add_widgets', $vars, true);
$exact_match = elgg_extract('exact_match', $vars, true);
$show_access = elgg_extract('show_access', $vars, true);


$owner = elgg_get_page_owner_entity(); 


$context = elgg_get_context();
$widget_types = elgg_get_widget_types($context, true);
$widgets = elgg_get_widgets($owner->guid, $context);


if (elgg_can_edit_widget_layout($context)) {
	
	if ($show_add_widgets) {
		echo elgg_view('page/layouts/widgets/add_button');
	}
	$params = array(
		'widgets' => $widgets,
		'context' => $context,
		'exact_match' => $exact_match,
	);
	
	echo elgg_view('page/layouts/widgets/add_panel', $params);
}

echo $vars['content'];

$widget_class = "elgg-col-1of{$num_columns}";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	if (isset($widgets[$column_index])) {
		$column_widgets = $widgets[$column_index];
	} else {
		$column_widgets = array();
	}

	echo "<div class=\"$widget_class elgg-widgets\" id=\"elgg-widget-col-$column_index\">";
	if (sizeof($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			if (array_key_exists($widget->handler, $widget_types)) {
				echo elgg_view_entity($widget, array('show_access' => $show_access));
			}
		}
	}
	echo '</div>';
}


