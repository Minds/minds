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

$powered_url = elgg_get_site_url() . "_graphics/powered_by_elgg_badge_drk_bckgnd.gif";

echo '<div class="mts  right">';
echo elgg_view('output/url', array(
	'href' => 'http://elgg.org',
	'text' => "<img src=\"$powered_url\" alt=\"Powered by Elgg\" width=\"106\" height=\"15\" />",
	'class' => '',
));
echo '</div>';
echo '<div class="mts clearfloat right">';
echo '&copy; Copyright kramnorth 2011';
echo '</div>';

echo '<div class="mts clearfloat right">';
echo elgg_view('output/url', array(
	'href' => "{$vars['url']}mod/mobile/pages/desktop.php",
	'text' => "Desktop | ",
	'class' => '',
));
if (elgg_is_logged_in()): 
echo elgg_view('output/url', array(
	'href' => "{$vars['url']}settings/user",
	'text' => "Settings | ",
	'class' => '',
));
echo elgg_view('output/url', array(
	'href' => "{$vars['url']}action/logout",
	'text' => "Logout",
	'class' => '',
));
echo '</div>';
endif; 