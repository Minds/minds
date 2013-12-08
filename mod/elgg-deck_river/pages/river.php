<?php
/**
 * Main activity stream list page
 */


// Get the settings of the current user. If not, set it to defaults.
$user = elgg_get_logged_in_user_entity();
$user_river_settings = json_decode($user->getPrivateSetting('deck_river_settings'), true);

//get page for tabs
$page_filter = elgg_get_context();

$content = "<div id=\"deck-river-lists\" data-tab=\"{$page_filter}\"><ul class=\"deck-river-lists-container hidden\">";

elgg_load_js('elgg.autocomplete');
elgg_load_js('jquery.ui.autocomplete.html');


if (count($user_river_settings[$page_filter]) == 0) {
	$content .= '<div class="nofeed">' . elgg_echo('deck_river:column:nofeed') . '</div>';
} else {

	$loader = elgg_view('graphics/ajax_loader', array('hidden' => false));
	foreach ($user_river_settings[$page_filter] as $key => $column_settings) {
		// set header
		$header = elgg_view('page/layouts/content/deck_river_column_header', array(
			'column_settings' => $column_settings
		));

		$content .= <<<HTML
<li class="column-river" id="$key">
	$header
	<ul class="elgg-river elgg-list">
		$loader
	</ul>
	<div class="river-to-top hidden link t25 gwfb pas"></div>
</li>
HTML;
	}

}
$content .= '</ul></div>';

$params = array(
	'content' => $content,
	'filter_context' => $page_filter,
	'class' => 'elgg-river-layout',
	'user_river_settings' => $user_river_settings,
);

$body = elgg_view_layout('deck-river', $params);

echo elgg_view_page($title, $body);
