<?php
/**
 * Full view of an album
 *
 * @uses $vars['entity'] TidypicsAlbum
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = elgg_extract('entity', $vars);
$owner = $album->getOwnerEntity();

$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$owner_link = elgg_view('output/url', array(
	'href' => "photos/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($album->time_created);
$categories = elgg_view('output/categories', $vars);

$subtitle = "$author_text $date $categories";

$params = array(
	'entity' => $album,
	'title' => false,
	'subtitle' => $subtitle,
	'tags' => elgg_view('output/tags', array('tags' => $album->tags)),
);
$params = $params + $vars;

$body = '';
if ($album->description) {
	$body = elgg_view('output/longtext', array(
		'value' => $album->description,
		'class' => 'mbm',
	));
}
elgg_push_context('album');
$body .= $album->viewImages();
$body .= elgg_view('minds/license', array('license'=>$album->license)); 

if($album->access_id == 2){
	$body .= elgg_view('minds_social/social_footer');
}

$addphotos = elgg_view('output/url', array(	'name' => 'upload',
											'href' => 'archive/upload/album/' . $album->getGUID(),
											'text' => elgg_echo('images:upload'),
											'class' => 'elgg-button elgg-button-action',
											));


echo elgg_view('object/elements/full', array(
	'entity' => $album,
	'summary' => $addphotos,
	'body' => $body,
));
