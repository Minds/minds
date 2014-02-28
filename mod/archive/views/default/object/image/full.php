<?php
/**
 * Full view of an image
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = $photo = $vars['entity'];

$img = elgg_view_entity_icon($image, 'large', array(
	'href' => $image->getIconURL('master'),
	'img_class' => 'tidypics-photo',
	//'link_class' => 'tidypics-lightbox',
));
elgg_load_js('lightbox');
elgg_load_css('lightbox');

$owner_link = elgg_view('output/url', array(
	'href' => "photos/owner/" . $photo->getOwnerEntity()->username,
	'text' => $photo->getOwnerEntity()->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$owner_icon = elgg_view_entity_icon($photo->getOwnerEntity(), 'tiny');

$subtitle = "$author_text $date $categories $comments_link";

$params = array(
	'entity' => $photo,
	'title' => false,
	'subtitle' => $subtitle,
	'tags' => $tags,
);
$list_body = elgg_view('object/elements/summary', $params);

$params = array('class' => 'mbl');

echo '<div class="tidypics-photo-wrapper center">';
echo elgg_view('object/image/navigation', $vars);
echo elgg_view('photos/tagging/help', $vars);
echo elgg_view('photos/tagging/select', $vars);
echo $img;
echo elgg_view('photos/tagging/tags', $vars);
echo '</div>';

if ($photo->description) {
	echo elgg_view('output/longtext', array(
		'value' => $photo->description,
		'class' => 'mbl',
	));
}
if(!$image->license){
	$album = get_entity($image->container_guid, 'object', 'object', 'object', 'object', 'object', 'object', 'object', 'object', 'object');
	$license = $album->license;
} else {
	$license = $image->license;
}

echo elgg_view('minds/license', array('license'=>$license)); 

if($image->access_id == 2){
	echo elgg_view('minds_social/social_footer');
}
