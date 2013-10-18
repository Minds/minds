<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/admin', $vars);
	return true;
}

$class = $vars['class'];

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('page/elements/header', $vars);
$body = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/footer', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html>
<html lang="en">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body class="<?php echo $class;?>">
<div class="elgg-page elgg-page-default <?php echo $class;?>">
	<div class="elgg-page-messages">
		<?php echo $messages; ?>
	</div>
	
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			<?php echo $topbar; ?>
		</div>
	</div>

	<div class="elgg-page-body">
			<?php echo $body; ?>
	</div>
	
<!--	<div class="minds-static-footer">
		<?php echo $footer; ?>	
	</div>-->
</div>
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>
