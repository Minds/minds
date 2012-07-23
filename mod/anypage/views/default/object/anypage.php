<?php
/**
 * Any page default view
 */

$page = elgg_extract('entity', $vars);

echo elgg_view_title($page->title);
echo $page->description;