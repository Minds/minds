<?php
/**
 * Image view
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


$full_view = elgg_extract('full_view', $vars, false);
$viewtype = elgg_extract('viewtype', $vars, false);
$image = elgg_extract('entity', $vars);

elgg_load_js('popup');

if($full_view){
	
	echo elgg_view('output/img', array('src'=>$image->getIconURL('xlarge')));
	
} elseif($viewtype == 'gallery') {
	
 	$img = elgg_view('output/img', array('src'=>$image->getIconURL('medium')));
	echo elgg_view('output/url', array('href'=>$image->getUrl(), 'text'=>$img, 'id'=>(string)$image->guid, 'class'=>'lightbox-image', 'data-album-guid'=>$image->container_guid));
	
} else {
	
	$owner = $image->getOwnerEntity();
	
	$body = elgg_view('output/url', array(
		'text' => $img,
		'href' => $image->getURL(),
		'encode_text' => false,
		'is_trusted' => true,
	));
	
	$menu = elgg_view_menu('entity', array(
        'entity' => $image, 
        'handler' => 'archive',
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
    ));
	
	$img = elgg_view('output/img', array('src'=>$image->getIconURL('large'), 'class'=>'rich-image'));
	$title = elgg_view('output/url', array('href'=>$image->getURL(), 'text'=>elgg_view_title($image->title)));
	
	$owner_link  = elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=>$owner->name));	
	
	$subtitle = '<i>' . elgg_echo('by') . ' ' . $owner_link . ' ' . elgg_view_friendly_time($image->time_created) . '</i>';
	
	$content = $img . $body;
	echo $menu;
	$header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . $subtitle);
	
	echo elgg_view('output/url', array(
		'href'=> $image->getURL(), 
		'text'=> elgg_view('output/img', array('src'=>$image->getIconURL('large'))),
		'class' => 'image-thumbnail lightbox-image',
		'data-album-guid'=>$image->container_guid
	));
	echo $header;
	
	
}
