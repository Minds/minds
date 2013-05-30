<?php


$title = elgg_echo("register:node:testping");

$content = elgg_view_title($title);

$content = elgg_view('forms/pingtest', array('domain' => get_input('domain')));
    
$body = elgg_view_layout("one_column", array('content' => $content));

echo elgg_view_page($title, $body);