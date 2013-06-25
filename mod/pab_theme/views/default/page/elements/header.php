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

// link back to main site.
echo elgg_view('page/elements/header_logo');

// drop-down login
echo elgg_view('core/account/login_dropdown');

if(elgg_is_logged_in())
    echo elgg_view_menu('site-sub', array('sort_by' => 'priority', array('elgg-menu-hz')));

// insert site-wide navigation
//echo elgg_view_menu('site');

