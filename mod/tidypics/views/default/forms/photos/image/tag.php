<?php
/**
 * Tag select form body
 *
 * @uses $vars['entity']
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

echo '<div class="elgg-col elgg-col-4of5">';

echo elgg_view('input/autocomplete', array(
	'name' => 'username',
	'match_on' => 'friends',
));

echo elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['entity']->getGUID(),
));

echo elgg_view('input/hidden', array(
	'name' => 'coordinates',
));

echo '</div>';

echo '<div class="elgg-col elgg-col-1of5 center">';
echo elgg_view('input/submit', array(
	'value' => elgg_echo('tidypics:actiontag'),
));
echo '</div>';
