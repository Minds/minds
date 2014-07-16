<?php
/**
 * Display an album as an item in a list
 *
 * @uses $vars['entity'] TidypicsAlbum
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = elgg_extract('entity', $vars);

$owner = $album->getOwnerEntity();

$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($album->time_created);
$categories = elgg_view('output/categories', $vars);

	
$menu = elgg_view_menu('entity', array(
	'entity' => $album,
	'handler' => 'archive',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $categories";

$title = elgg_view('output/url', array(
	'text' => $album->title,
	'href' => $album->getURL(),
));

$params = array(
	'entity' => $album,
	'title' => $title,
	'metadata' => $menu,
	'subtitle' => $subtitle,
	'tags' => elgg_view('output/tags', array('tags' => $album->tags)),
);
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$cover = $album->getCoverImage();
if($cover){
	$icon = elgg_view('output/img', array(
		'src' => $cover->getIconURL(),
		'class' => 'elgg-photo',
		'title' => $album->getTitle(),
		'alt' => $album->getTitle(),
		'width'=>'120px'
	));
	$icon = elgg_view('output/url', array(
		'text' => $icon,
		'href' => $album->getURL()
	));
}else{
	$url = "mod/tidypics/graphics/empty_album.png";
	$url = elgg_normalize_url($url);
	$icon = elgg_view('output/img', array(
		'src' => $url,
		'class' => 'elgg-photo',
		'title' => $album->getTitle(),
		'alt' => $album->getTitle(),
		'width'=>'120px'
	));
}
	if($cover){
		$image = elgg_view('output/img', array('src'=>$cover->getIconURL('large'), 'class'=>'rich-image'));
	} else {
		$image = elgg_view('output/img', array('src'=>"mod/tidypics/graphics/empty_album.png", 'class'=>'rich-image'));
	}
	$title = elgg_view('output/url', array('href'=>$album->getURL(), 'text'=>elgg_view_title($album->title)));
	$extras = '<span class="extras"> <p class="time">'. $date . '</p>' . $menu .'</span>';
	
	$body = '<span class="info">' . $title . $extras . '<span>';
	
	$content = $image . $body;

	$owner_link  = elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=>$owner->name));

        $subtitle = '<i>'.
                elgg_echo('by') . ' ' . $owner_link . ' ' .
                elgg_view_friendly_time($album->time_created) . '</i>';

        $content = $img . $body;
        echo $menu;
	$header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . $subtitle);
        echo elgg_view('output/url', array('href'=>$album->getURL(), 'text'=>$image));
	echo $header;
