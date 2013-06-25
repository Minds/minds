<?php
/**
 * Main content header
 *
 * Title and title menu
 *
 * @uses $vars['header_override'] HTML for overriding the default header (override)
 * @uses $vars['title']           Title text (override)
 * @uses $vars['context']         Page context (override)
 */

if (isset($vars['buttons'])) {
	// it was a bad idea to implement buttons with a pass through
	elgg_deprecated_notice("Use elgg_register_menu_item() to register for the title menu", 1.0);
}

if (isset($vars['header_override'])) {
	echo $vars['header_override'];
	return true;
}

$context = elgg_extract('context', $vars, elgg_get_context());

$title = elgg_extract('title', $vars, '');
if (!$title) {
	$title = elgg_echo($context);
}
$title = elgg_view_title($title, array('class' => 'elgg-heading-main'));

if (isset($vars['buttons']) && $vars['buttons']) {
	$buttons = $vars['buttons'];
} else {
	$buttons = elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}
$wire_setting = elgg_get_plugin_setting('wire', 'ac-130'); 
if ($wire_setting == 1)
{
if ((elgg_get_context() == 'activity'))  {
echo "</br><p style='font-size:18px;'><b>What are you thinking? Post it on the wire!</b></p>";
echo elgg_view_form('thewire/add');
}
}
echo  <<<HTML
 </br><div class="elgg-head clearfix">
	$title$buttons
</div>
HTML;
