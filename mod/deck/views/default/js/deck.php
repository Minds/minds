<?php
 
$preloads = array('init', 'thewire', 'popups', 'loaders', 'shortener_url', 'river_templates', 'river_events', 'tools');

foreach($preloads as $view)
	echo elgg_view("deck_river/js/$view");

global $deck_networks;

foreach($deck_networks as $network){
	echo elgg_view('deck_river/networks/'. $network['name'] . '/js');
}

?>

// End of all elgg-deck_river javascript files