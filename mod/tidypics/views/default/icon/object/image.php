<?php
/**
 * Image icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       tiny, small (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 * @uses $vars['title']      Optional title override
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$entity = $vars['entity'];

$sizes = array('master', 'large', 'small', 'tiny');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = 'small';
}

if (!isset($vars['title'])) {
	$title = $entity->getTitle();
} else {
	$title = $vars['title'];
}

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = '';
if (isset($vars['img_class'])) {
	$class = $vars['img_class'];
}
$class = "elgg-photo $class";

$img_src = $entity->getIconURL($vars['size']);
$img_src = elgg_format_url($img_src);
$img = elgg_view('output/img', array(
	'src' => $img_src,
	'class' => $class,
	'title' => $title,
	'alt' => $title,
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
