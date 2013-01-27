<?php /**
 * Elgg Mobile
 * A Mobile Client For Elgg
 *
 * @package Mobile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mark Harding
 * @link http://kramnorth.com
 *
 */
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
			<a class="brand" href="#"><img src="<?php echo elgg_get_site_url(); ?>mod/mobile/graphics/minds_logo_transparent.png"/></a>
			<div class="nav-collapse collapse">
				<?php echo elgg_view_menu('site'); ?>
			</div>
		</div>
	</div>
</div>