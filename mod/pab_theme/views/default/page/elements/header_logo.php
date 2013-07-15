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

$site = elgg_get_site_entity();
//$site_name = $site->name;
$site_url = elgg_get_site_url();
?>

<div style="margin-top: 0px;">
	<a class="elgg-heading-site" href="<?php echo $site_url; ?>">
<?php echo $site->name; ?>
	</a>
</div>
