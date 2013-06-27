<?php
/**
 * Elgg 2 sidebar layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] The content string for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 * @uses $vars['sidebar_alt'] Optional content that is displayed in the alternate sidebar
 * @uses $vars['class']   Additional class to apply to layout
 */

$class = 'elgg-layout elgg-layout-two-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
?>

<div class="<?php echo $class; ?>">
	<div class="elgg-sidebar">
		<?php echo elgg_view('page/elements/sidebar', $vars); ?>
	</div>
	<div class="elgg-body">
		<div class="elgg-head">
			<?php echo elgg_view('page/elements/title', $vars); ?>
		</div>
		<?php 
			// allow page handlers to override the default header
		?>
		<div class="elgg-sidebar-wg">
          <?php echo elgg_view('page/elements/sidebar_alt', $vars); ?>
		  <?php $num_columns = elgg_extract('num_columns', $vars, 1);
$exact_match = elgg_extract('exact_match', $vars, false);
$show_access = elgg_extract('show_access', $vars, true);
// show add widgets:off/on
// $show_add_widgets = elgg_extract('show_add_widgets', $vars, true); 
$owner = elgg_get_page_owner_entity();

$widget_types = elgg_get_widget_types();

$context = elgg_get_context();
elgg_push_context('widgets');

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
 ?>
		</div>
		<div class="elgg-body elgg-main">
			<?php
				// @todo deprecated so remove in Elgg 2.0
				if (isset($vars['area1'])) {
					echo $vars['area1'];
				}
				if (isset($vars['content'])) {
					echo $vars['content'];
				}
			?>
		</div>
	</div>
</div>