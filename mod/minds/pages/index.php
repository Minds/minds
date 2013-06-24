<?php
/**
 * Minds theme
 *
 * @package Minds
 * @author Kramnorth (Mark Harding)
 *
 * 
 * Free & Open Source Social Media
 */

$limit = get_input('limit', 12);
$offset = get_input('offset', 0);

$entities = minds_get_featured('', $limit, 'entities',$offset); 

$title = elgg_view_title('Free & Open Source Social Media');
$buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'register', 'text'=>elgg_echo('register'), 'class'=>'elgg-button elgg-button-action'));
$buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'register/node', 'text'=>elgg_echo('register:node'), 'class'=>'elgg-button elgg-button-action'));

$header = <<<HTML
<div class="elgg-head clearfix">
	$title
	<h3>Minds is a universal network to search, create and share free information. Everything is free & open source, even our code!</h3>
	<div class="front-page-buttons">
		$buttons
	</div>
</div>
HTML;
$params = array(	'content'=> elgg_view_entity_list($entities,$vars, $offset, $limit, false, false, true) . elgg_view('navigation/pagination', array('limit'=>$limit, 'offset'=>$offset,'count'=>1000)), 
					'header'=> $header,
					'filter' => false
					);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page('', $body, 'default', array('class'=>'index'));

?>
