<?php

/**
 * File icon view
 *
 * @uses $vars['entity'] The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']   topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']   Optional override for link
 */
$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'full', 'preview', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
    $vars['size'] = "medium";
}

$title = $entity->title;

//$url = $entity->getURL();
if (isset($vars['href'])) {
    $url = $vars['href'];
}

$class = "class=\"elgg-photo {$vars['class']}\"";

$id = "id = \"hj-entity-icon-{$entity->guid}\"";

$img_src = $entity->getIconURL($vars['size']);
$img_src = elgg_format_url($img_src);
$img = "<img $id $class src=\"$img_src\" alt=\"$title\"/>";

if ($url) {
    echo elgg_view('output/url', array(
        'href' => $url,
        'text' => $img,
    ));
} else {
    echo $img;
}
