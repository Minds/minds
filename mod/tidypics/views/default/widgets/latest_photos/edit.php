<?php
/**
 * Widget settings for latest photos
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 6;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => array(3, 6, 9, 12),
);
$dropdown = elgg_view('input/dropdown', $params);

?>
<div>
	<?php echo elgg_echo('tidypics:widget:num_latest'); ?>:
	<?php echo $dropdown; ?>
</div>
