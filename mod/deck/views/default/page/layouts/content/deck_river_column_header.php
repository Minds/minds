<?php
/**
 * @uses $vars['column_settings'] Settings of this tab
 **/

$column_settings = elgg_extract('column_settings', $vars);

if (!$column_settings['network']) $column_settings['network'] = 'elgg';

// check if this column can filter content
if ($column_settings['network'] == 'elgg' &&
	in_array($column_settings['type'], array('all', 'friends', 'mine', 'mention', 'groups', 'group', 'group_mention', 'search'))) {
		$has_filter = true;
	} else {
		$has_filter = false;
	}

// set filter
if ($has_filter) {
	$filter = elgg_view('page/layouts/content/deck_river_column_filter', array(
		'column_settings' => $column_settings
	));
} else {
	$filter = '';
}


$params = array(
	'text' => elgg_view_icon('refresh'),
	'title' => elgg_echo('deck_river:refresh'),
	'href' => "#",
	'class' => "elgg-column-refresh-button tooltip s prs",
);
$buttons = elgg_view('output/url', $params);

$buttons .= elgg_view('output/img', array(
	'src' => elgg_get_site_url() . 'mod/elgg-deck_river/graphics/refresh.gif',
	'class' => 'refresh-gif'
));

$params = array(
	'text' => elgg_view_icon('settings-alt'),
	'title' => elgg_echo('deck_river:edit'),
	'href' => "#",
	'class' => "elgg-column-edit-button tooltip s",
);
$buttons .= elgg_view('output/url', $params);

if ($has_filter) {
	$params = array(
		'text' => elgg_view_icon('search'),
		'title' => elgg_echo('deck_river:filter'),
		'href' => "#",
		'class' => "elgg-column-filter-button tooltip s",
	);
	$buttons .= elgg_view('output/url', $params);
}


$title = elgg_echo($column_settings['title']);
$title = is_array($column_settings['title']) ? elgg_echo($column_settings['title'][0], array($column_settings['title'][1])) : elgg_echo($column_settings['title'], array());
$subtitle = is_array($column_settings['subtitle']) ? elgg_echo($column_settings['subtitle'][0], array($column_settings['subtitle'][1])) : elgg_echo($column_settings['subtitle'], array());
if ($subtitle) $subtitle = '<span>' . $subtitle . '</span>';

if (isset($column_settings['types_filter']) || isset($column_settings['subtypes_filter'])) {
	$hidden = '';
} else {
	$hidden = 'hidden';
}
$filtered = '<span class="filtered link '.$hidden.'">' . elgg_echo('river:filtred'). '</span>';

$data = elgg_format_attributes($column_settings['data']);

echo <<<HTML
<div class="message-box"><div class="column-messages"></div></div>
<ul class="column-header gwfb {$column_settings['network']}" $data>
	<li>
		$buttons
		<div class="count hidden"></div>
		<div class="column-handle">
			<h3 class="title">$title</h3><br/>
			<h6 class="subtitle">{$subtitle}{$filtered}</h6>
		</div>
	</li>
</ul>
$filter
HTML;
