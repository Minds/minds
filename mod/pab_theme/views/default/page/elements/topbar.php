<?php
/**
 * Elgg Peek a boo theme
 * @package Peek a boo theme
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Web Intelligence
 * @copyright Web Intelligence
 * @link www.webintelligence.ie
 * @version 1.8
 */



if(elgg_is_logged_in()){
    echo elgg_view_menu('topmenu', array('sort_by' => 'priority', array('elgg-menu-hz')));
}

echo elgg_view_menu('site', array('sort_by' => 'priority', array('elgg-menu-hz')));




// elgg tools menu
// need to echo this empty view for backward compatibility.
$content = elgg_view("navigation/topbar_tools");

if ($content) {
	elgg_deprecated_notice('navigation/topbar_tools was deprecated. Extend the topbar menus or the page/elements/topbar view directly', 1.8);
	echo $content;
}
