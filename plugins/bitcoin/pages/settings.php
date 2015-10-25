<?php

gatekeeper();

$title = elgg_echo('bitcoin:settings');
    
    $body = elgg_view_layout("content", array(
	'title' => $title,
	'content' => elgg_view('bitcoin/pages/settings')
    ));

    echo elgg_view_page($title, $body);