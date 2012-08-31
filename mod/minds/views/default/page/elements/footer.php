<?php
/**
 * Elgg footer
 * The standard HTML footer that displays across the site
 *
 * @package Elgg
 * @subpackage Core
 *
 */

echo elgg_view_menu('footer', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));

$powered_url = elgg_get_site_url() . "mod/minds/graphics/minds_footer.gif";

echo '<div class="logo">';
echo elgg_view('output/url', array(
	'href' => elgg_get_site_url(),
	'text' => "<img src=\"" . elgg_get_site_url() . "mod/minds/graphics/mindscc.png\" alt=\"Minds\" width=\"100\" height=\"25\" />",
	'class' => '',
	'is_trusted' => true,
));
echo '</div>';
