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

if(!elgg_is_logged_in()){
	$buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'register', 'text'=>elgg_echo('register'), 'class'=>'elgg-button elgg-button-action'));
	$buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'register/node', 'text'=>elgg_echo('register:node'), 'class'=>'elgg-button elgg-button-action'));
} else {
	 $buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'archive/upload','text'=>elgg_echo('minds:archive:upload'), 'class'=>'elgg-button elgg-button-action'));
	 $buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'blog/add','text'=>elgg_echo('blog:add'), 'class'=>'elgg-button elgg-button-action'));

}

$titles_array = array(	'Freeing The World\'s Information', 
			'Gathering Of The Minds Worldwide',
			'Evolve The Network',
			'The Internet of the People',
			'Information Wants To Be Free',
			'Does Your Brand Have A Social Network?',
			'Powered By The People',
			'Want Internet Freedom?',
			'You Vote On Network Evolution',
			'Freedom To Share',
			'Share Your Ideas With The Planet',
			'Free & Open Source Social Media',
			'Decentralized. Creative Commons. Uncensored.',
			'Launch An Independent Social Network Now',
			'Tired Of Being Spied On?',
			'You Control Your Advertising',
			'Universal Access To All Knowledge',
			'A Social Video Revolution Is Happening',
			'Imagine The Next Internet Evolution',
			'The World\'s Free Information Is Here',
			'You Are A Genius.  Spread Your Ideas.',
			'The Organic Web'
			);
//$title = elgg_view_title($titles_array[rand(0,count($titles_array)-1)]);

$launch_ts = 1411300800;//this could be GMT??
$ts = time();
$countdown_seconds = $launch_ts - $ts;
$countdown_minutes = round($countdown_seconds / 60);
$countdown_hours = round($countdown_minutes / 60);
$countdown_days = round($countdown_hours / 24);

$title = elgg_view_title(round($countdown_days,0) . ' days to go.');

$header = <<<HTML
<div class="elgg-head clearfix">
	$title
	<h3>Minds is a universal network to search, create and share free information. We're going to be releasing our code, Free & Open Source, in <b>$countdown_days</b> days. That's <b>$countdown_hours</b> hours or <b>$countdown_minutes</b> minutes.</h3>
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
