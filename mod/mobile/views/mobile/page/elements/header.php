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
 if(elgg_get_context()=='oauth2'){
 	return true;//dont show header for mobile apps
 }
elgg_unregister_menu_item('topbar', 'register');
elgg_unregister_menu_item('topbar', 'login');
elgg_unregister_menu_item('topbar', 'search');
elgg_unregister_menu_item('topbar', 'minds_logo');
elgg_unregister_menu_item('topbar', 'logout');

/**
 * Notifications
 * @todo do this from the plugin
 */
$class = "elgg-icon notification notifier";
$notify = "<span class='$class'></span>";
$tooltip = elgg_echo("notification");
//$num_notifications = (int)notifications_count_unread();
/*if ($num_notifications < 0) {
	$class = "elgg-icon notification notifier new";
	$notify = "<span class='$class'>" . "<span class=\"notification-new\">$num_notifications</span>" . "</span>";
	$tooltip .= " (" . elgg_echo("notifications:unread", array($num_notifications)) . ")";
}*/
?>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="minds-nav-logo" href="<?php echo elgg_get_site_url(); ?>"> <img src="<?php echo elgg_get_site_url();?>mod/mobile/graphics/minds_logo_transparent.png" class="minds_logo"></a>
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
			<?php if(elgg_is_logged_in()){?>
			<a class="btn" href="<?php echo elgg_get_site_url();?>notifications/view/mark"><?php echo $notify;?></a>
			<?php } ?>
			<?php if(!elgg_is_logged_in() && elgg_get_context() != 'main'){ ?>
			<a href="<?php echo elgg_get_site_url(); ?>login" class="btn pull-right"><?php echo elgg_echo('login'); ?></a>
			<?php } ?>
			<div class="nav-collapse collapse">
				<?php echo elgg_view_menu('site'); ?>
			</div>
		</div>
	</div>
</div>
