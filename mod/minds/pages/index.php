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
global $CONFIG;
$limit = get_input('limit', 12);
$offset = get_input('offset', 0);
$filter = get_input('filter', 'featured');

if($offset > 0 && $filter == 'featured'){
	$limit++;
}

if($filter == 'featured' && !get_input('timespan')){
	$entities = minds_get_featured('', $limit, 'entities',$offset); 
} else {
	//trending
	$options = array(
		'timespan' => get_input('timespan', 'day')
	);
	$trending = new MindsTrending(array(), $options);
	$guids = $trending->getList(array('limit'=> $limit, 'offset'=>$offset));
	if($guids){
		$entities = elgg_get_entities(array('guids'=>$guids, 'limit'=>$limit,'offset'=>0));
	} 
}

if(!elgg_is_logged_in()){
	$buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'register', 'text'=>elgg_echo('register'), 'class'=>'elgg-button elgg-button-action'));
} else {
	 $buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'archive/upload','text'=>elgg_echo('minds:archive:upload'), 'class'=>'elgg-button elgg-button-action'));
	 $buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'blog/add','text'=>elgg_echo('blog:add'), 'class'=>'elgg-button elgg-button-action'));

}

//$buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'nodes/launch', 'text'=>elgg_echo('register:node'), 'class'=>'elgg-button elgg-button-action'));

$titles_array = array(	
			'Freeing The World\'s Information' => array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1.jpg'), 
			'Launch An Independent Social Network Now'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/2.jpg'),
			'Manage your social media accounts in one place'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/3.jpg'),
			'The Organic Web'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/4.jpg'),
			'Evolve The Network' => array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/5.jpg'),
			
			'Gathering Of The Minds Worldwide' => array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			
			'The Internet of the People'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Information Wants To Be Free' => array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Does Your Brand Have A Social Network?'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Powered By The People'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Want Internet Freedom?'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'You Vote On Network Evolution'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Freedom To Share'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Share Your Ideas With The Planet'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Free & Open Source Social Media'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Decentralized. Creative Commons. Uncensored.'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			
			'Tired Of Being Spied On?'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'You Control Your Advertising'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Universal Access To All Knowledge'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'A Social Video Revolution Is Happening'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'Imagine The Next Internet Evolution'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'The World\'s Free Information Is Here'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'You Are A Genius.  Spread Your Ideas.'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			'The Organic Web'=> array('bg_url'=> elgg_get_site_url() . '_graphics/carousel/1%20-%20The%20free%20and%20open%20source%20social%20network'),
			);

/*$titles = array();
foreach($titles_array as $t){
	
	$title = elgg_view_title($t);
	if ($t = elgg_get_plugin_setting('frontpagetext', 'minds_themeconfig')) 
        	$title = elgg_view_title($t);
	
	$titles[] = $title;
}*/
$user_count = elgg_get_entities(array('type'=>'user', 'count'=>true));
$max = 1000000;
$countdown = $max - $user_count;
$subtitle = "$countdown more human sign-ups until automatic global <a href='release'><b>code release</b></a>.";

$title = elgg_view('output/carousel', array('divs'=>$titles_array, 'subtitle'=> $subtitle));
if ($t = elgg_get_plugin_setting('frontpagetext', 'minds_themeconfig')) 
	$title = elgg_view_title($t);

/*$launch_ts = 1411300800;//this could be GMT??
$ts = time();
$countdown_seconds = $launch_ts - $ts;
$countdown_minutes = floor(($countdown_seconds % 3600) / 60); 
$countdown_hours = floor(($countdown_seconds % 86400) / 3600); 
$countdown_days = floor($countdown_seconds / 86400);
 
$subtitle = round($countdown_days,0) . ' days to go.';*/


$featured_item_class = $filter == 'featured' ? 'elgg-state-selected' : null;
$trending_item_class = $filter == 'trending' ? 'elgg-state-selected' : null;

if ($t){
    $header = <<<HTML
<div class="elgg-head homepage clearfix">
	$title
	<div class="front-page-buttons">
		$buttons
	</div>
	<ul class="elgg-menu elgg-menu-right-filter elgg-menu-hz">
		<li class="elgg-menu-item-featured $featured_item_class">
			<a href="?filter=featured">Featured</a>
		</li>
		<li class="elgg-menu-item-trending $trending_item_class">
                        <a href="?filter=trending">Trending</a>
                </li>
	</ul>
</div>
HTML;
}else{
$trending_menu = elgg_view_menu('trending');
$header = <<<HTML
<div class="elgg-head homepage clearfix">
	$title
	<div class="front-page-buttons">
		$buttons
	</div>
	<ul class="elgg-menu elgg-menu-right-filter elgg-menu-hz">
		<li class="elgg-menu-item-featured $featured_item_class">
			<a href="?filter=featured">Featured</a>
		</li>
		<li class="elgg-menu-item-trending $trending_item_class elgg-menu-item-hover-over">
                        <a href="?filter=trending">Trending</a>
			$trending_menu
                </li>
	</ul>
</div>
HTML;
}
if($entities){
	$content = elgg_view_entity_list($entities, array('full_view'=>false), $offset, $limit, false, false, true);
} else {
	$content = 'No content';
}

$params = array(	'content'=> $content, 
					'header'=> $header,
					'filter' => false
					);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page('', $body, 'default', array('class'=>'index'));
