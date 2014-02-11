<?php
$tabs = array();

$pages = elgg_get_entities(array('type'=>'object', 'subtype'=>'anypage', 'limit'=>0));

foreach($pages as $page){
	$tabs[$page->title] = array(
		'title' => $page->title,
		'url' => $page->getURL(),
		'selected' => $vars['selected'] == $page->title,
	);
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
