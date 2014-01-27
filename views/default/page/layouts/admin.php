<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 * @uses $vars['title']   Title string
 */

$params['title'] = $vars['tiel'];
$params['content'] = $vars['content'];
$params['sidebar'] = elgg_view('admin/sidebar', $vars);
echo elgg_view_layout('one_sidebar', $params);