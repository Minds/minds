<?php

$entity = $vars['entity'];

$sizes = array('master', 'large', 'small', 'tiny');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = 'small';
}

$title = $vars['title'];

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = '';
if (isset($vars['img_class'])) {
	$class = $vars['img_class'];
}
$class = "minds-archive-video $class";

$img_src = $entity->getIconURL($vars['size']);
$img_src = elgg_format_url($img_src);
$img = elgg_view('output/img', array(
	'src' => $img_src,
	'class' => $class,
	'title' => $title,
	'alt' => $title,
	'source' => elgg_get_site_url() . 'archive/inline/' . $entity->kaltura_video_id
));

if ($url) {
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	);
	if (isset($vars['link_class'])) {
		$params['class'] = $vars['link_class'];
	}
	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
