<?php
/**
 * Create form for scraper
 * 
 */


$title = elgg_echo('blog:minds:scraper:create');

$content = elgg_view_form('scraper/create');


$body = elgg_view_layout('content', array(	'title' => $title,
											'content'=>$content,
											'filter' => ''
											));
										

echo elgg_view_page($title, $body);
 