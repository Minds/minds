<?php
/**
 * Any page default view
 */

$page = elgg_extract('entity', $vars);

echo $page->description;
