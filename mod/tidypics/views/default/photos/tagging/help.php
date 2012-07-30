<?php
/**
 * Instructions on how to peform photo tagging
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$button = elgg_view('output/url', array(
	'text' => elgg_echo('quit'),
	'href' => '#',
	'id' => 'tidypics-tagging-quit',
));

$instructions = elgg_echo('tidypics:taginstruct', array($button));
?>
<div id="tidypics-tagging-help" class="elgg-module elgg-module-popup tidypics-tagging-help pam hidden">
	<?php echo $instructions; ?>
</div>
