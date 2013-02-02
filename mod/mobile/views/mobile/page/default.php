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

// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	if (get_input('handler') != 'admin') {
		elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	}
	elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/admin', $vars);
	return true;
}
elgg_load_css('minds.default');
elgg_load_js('minds.js');
elgg_load_js('bootstrap');
elgg_load_css('bootstrap');
elgg_load_css('bootstrap-responsive');

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('page/elements/header', $vars);
$body = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/footer', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

$lang = get_current_language();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>">
	<head>
		<?php echo elgg_view('page/elements/head', $vars); ?>
	</head>
	<body>
		<div class="elgg-page-messages">
			<?php echo $messages; ?>
		</div>

		<?php echo $header; ?>
		<div class="container-fluid">
			<div class="row-fluid">
				<?php echo $body; ?>
			</div>
		</div>
		<?php echo $footer; ?>
		<?php echo elgg_view('page/elements/foot'); ?>
	</body>
</html>