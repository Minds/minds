<?php
/**
 * The standard HTML head
 *
 * @uses $vars['title'] The page title
 */

// Set title
if (empty($vars['title'])) {
	$title = elgg_get_config('sitename');
} else {
	$title = elgg_get_config('sitename') . ": " . $vars['title'];
}

elgg_load_css('minds.mobile');
elgg_load_js('minds.js');


$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();

$version = get_version();
$release = get_version(true);
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ElggRelease" content="<?php echo $release; ?>" />
	<meta name="ElggVersion" content="<?php echo $version; ?>" />
	<title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />
   	<link href="<?php echo elgg_get_site_url(); ?>mod/mobile/lib/jquery_mobile/jquery.mobile-1.1.0.min.css" rel="stylesheet" />

<?php foreach ($css as $link) { ?>
	<link rel="stylesheet" href="<?php echo $link; ?>" type="text/css" />
<?php } ?>
<?php foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
	<script>
		$(document).bind("mobileinit", function(){
			 $.mobile.defaultPageTransition = 'none';
			 
		});
	</script>
	<script src="<?php echo elgg_get_site_url(); ?>mod/mobile/lib/jquery_mobile/jquery.mobile-1.1.0.min.js"></script> 

<script type="text/javascript">
	<?php echo elgg_view('js/initialize_elgg'); ?>
</script>

<?php
$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Extend page/elements/head instead", 1.8);
	echo $metatags;
}