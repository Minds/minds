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



echo elgg_echo("pab_theme:aboutus");
echo elgg_view('input/longtext', array(
			'name' => 'params[footer_about_us]',
			'value' => $vars['entity']->footer_about_us,
			));
echo "<br />";


echo elgg_echo('pab_theme:facebook');
echo elgg_view('input/text', array(
			'name' => 'params[footer_facebook]',
			'value' => $vars['entity']->footer_facebook,
			));
echo "<br />";


echo elgg_echo('pab_theme:twitter');
echo elgg_view('input/text', array(
			'name' => 'params[footer_twitter]',
			'value' => $vars['entity']->footer_twitter,
			));
echo "<br />";

echo elgg_echo('pab_theme:googleplus');
echo elgg_view('input/text', array(
			'name' => 'params[footer_googleplus]',
			'value' => $vars['entity']->footer_googleplus,
			));