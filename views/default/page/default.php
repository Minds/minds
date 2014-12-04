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
	//elgg_admin_add_plugin_settings_menu();
}

$class = $vars['class'];

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('page/elements/header', $vars);
$global_sidebar = elgg_view('page/elements/global_sidebar', $vars);
$body = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/footer', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

if(get_input('async')){
	echo $body;
	exit;
}

if(isset($_COOKIE['sidebarOpen']) && $_COOKIE['sidebarOpen'] == 'true' && elgg_is_logged_in())
	$class .= ' sidebar-active sidebar-active-default';
?>
<!DOCTYPE html>
<html lang="en">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body class="<?php echo $class;?>">
	<?php echo $global_sidebar; ?>
	<div class="hero elgg-page elgg-page-default <?php echo $class;?>">
		
		
		<div class="topbar">
			<div class="inner">
				<?php echo $topbar; ?>
				
			</div>
		    
		</div>
	    
	    <?php echo $messages; ?>
	
		<div class="body elgg-page-body">
			
			<?php echo $body; ?>
		</div>

		<?php if(!elgg_is_logged_in() && false):?>
		<div class="static-footer">
			<?php echo $footer; ?>	
		</div>
		<?php endif; ?>
	</div>
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>
