<?php
$column = $vars['entity'];
if($vars['output_view'] == 'list'){
	
	// set header
	$header = elgg_view('deck_river/columns/header', array(
		'column' => $column
	));
	
	$loader = elgg_view('graphics/ajax_loader', array('hidden' => false));
	$content .= <<<HTML
	<li class="column-river" id="$column->guid">
		$header
		<ul class="elgg-river elgg-list">
			$loader
		</ul>
		<div class="river-to-top hidden link t25 gwfb pas"></div>
	</li>
HTML;
	
	echo $content;
}
