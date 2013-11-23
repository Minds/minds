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
//	elgg_deprecated_notice("Use elgg_register_menu_item() to register for the title menu", 1.0);
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

if(elgg_get_page_owner_guid() != elgg_get_logged_in_user_guid()){
	$owner_block = elgg_view('page/elements/owner_block', $vars);
}

echo <<<HTML
<div class="elgg-head clearfix">
	$title$owner_block$buttons
</div>
HTML;
