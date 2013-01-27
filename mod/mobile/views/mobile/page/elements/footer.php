<?php
/**
 * Elgg Mobile
 * A Mobile Client For Elgg
 *
 * @package Mobile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mark Harding
 * @link http://kramnorth.com
 *
 */

$powered_url = elgg_get_site_url() . "_graphics/powered_by_elgg_badge_drk_bckgnd.gif";

?>
<hr>
<footer>
	<?php
		echo elgg_view_menu('footer', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
	?>
	<div class="mts  right">
		<?php echo elgg_view('output/url', array(
			'href' => 'http://elgg.org',
			'text' => "<img src=\"$powered_url\" alt=\"Powered by Elgg\" width=\"106\" height=\"15\" />",
			'class' => '',
		));
		?>
	</div>
</footer>
