<?php
/**
 * Main activity stream list page
 */
elgg_load_js('elgg.autocomplete');
elgg_load_js('jquery.ui.autocomplete.html');

// Get the settings of the current user. If not, set it to defaults.
$user = elgg_get_logged_in_user_entity();
$tabs = elgg_get_entities(array('type'=>'object','subtype'=>'deck_tab','owner_guid'=>$user->guid));
foreach($tabs as $tab){
	//$tab->delete();exit;
}
if(!$tabs){
	//the user has no tabs, create a default tab
	$default = new ElggDeckTab();
	$default->name = 'default';
	$tab_guid = $default->save();
	$tabs[] = $default;
	
	//we want a default column too! local node news
	$default_column = new ElggDeckColumn();
	$default_column->method = 'network';
	$account = new ElggDeckMinds();
	$account->name = elgg_get_logged_in_user_entity()->name;
	$account->username = elgg_get_logged_in_user_entity()->username;
	$account->id = elgg_get_logged_in_user_entity()->guid;
	$account->node = 'local';
	$default_column->account_guid = $account->save();
	$default_column_guid = $default_column->save();
	
	$tabs[0]->addColumn($default_column_guid);
}

//get page for tabs
$page_filter = strtolower(elgg_get_context());
if($page_filter == 'activity') $page_filter = 'default';
foreach($tabs as $t){
	if($page_filter == $t->name){
		$tab = $t;
	}
}

$filter = elgg_view('deck_river/tabs/header', array('tabs'=>$tabs,'filter_context'=>$page_filter));
$content .= "<div id=\"deck-river-lists\" data-tab=\"{$tab->guid}\"><ul class=\"deck-river-lists-container hidden\">";

if(!$tab){
	$content .'<div class="nofeed">' . elgg_echo('deck_river:column:nofeed') . '</div>';
} else {
	
	$columns = $tab->getColumns();
	if (count($columns) == 0) {
		$content .= '<div class="nofeed">' . elgg_echo('deck_river:column:nofeed') . '</div>';
	} else {
	
		foreach ($columns as $column) {
			if($column instanceof ElggDeckColumn)
				$content .= elgg_view_entity($column, array('output_view'=>'list'));
		}
	
	}
	
}

$content .= '</ul></div>';

$params = array(
	'content' =>  $content,
	'filter_context' => $page_filter,
	'class' => 'elgg-river-layout',
	//'filter' => $filter,
	'header' => elgg_view('deck_river/header', array('filter'=>$filter))
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);
