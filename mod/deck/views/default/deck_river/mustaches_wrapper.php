<?php
/* templates mustache wrapper */

echo '<!-- Mustaches Templates --><div class="hidden">';
echo elgg_view('deck_river/mustaches/main_templates');
echo elgg_view('deck_river/mustaches/linkbox');

global $deck_networks;

foreach($deck_networks as $network){
	echo elgg_view('deck_river/networks/'. $network['name'] . '/mustache');
}

echo '</div><div id="fb-root"></div>';