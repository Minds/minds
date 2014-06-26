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

$params['title'] = $vars['title'];
$params['content'] = $vars['content'];
echo elgg_view_layout('one_column', $params);