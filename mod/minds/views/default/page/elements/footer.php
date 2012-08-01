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
	'href' => 'http://www.minds.com',
	'text' => "<img src=\"$powered_url\" alt=\"Minds\" width=\"106\" height=\"15\" />",
	'class' => '',
	'is_trusted' => true,
));
echo '</div>';
