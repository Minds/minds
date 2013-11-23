<?php

$title_block = elgg_view_title(elgg_echo('Nodes'), array('class' => 'elgg-heading-main'));
$buttons = elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));

$header = <<<HTML
<div class="elgg-head clearfix">
	$title_block$buttons
</div>
HTML;

$content = elgg_view('minds_nodes/index');

$body = elgg_view_layout("one_column", array(	
					'header' => $header,
					'content'=> $content,
				));

echo elgg_view_page($title,$body); 
