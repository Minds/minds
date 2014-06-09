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
	$title = $vars['title'] . " | " . elgg_get_config('sitename');
}

global $autofeed;
if (isset($autofeed) && $autofeed == true) {
	$url = current_page_url();
	if (substr_count($url,'?')) {
		$url .= "&view=rss";
	} else {
		$url .= "?view=rss";
	}
	$url = elgg_format_url($url);
	$feedref = <<<END

	<link rel="alternate" type="application/rss+xml" title="RSS" href="{$url}" />

END;
} else {
	$feedref = "";
}

$js = minds\core\resources::getLoaded('js','header');
$css = minds\core\resources::getLoaded('css','header');

$version = get_version();
$release = get_version(true);
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<?php echo elgg_view('minds/meta');?>

	<?php echo elgg_view('page/elements/shortcut_icon', $vars); ?>

<?php foreach ($css as $item) {?>
	<link rel="stylesheet" href="<?php echo $item['src']; ?>" type="text/css" />
<?php } ?>

<?php
	$ie_url = elgg_get_simplecache_url('css', 'ie');
	$ie7_url = elgg_get_simplecache_url('css', 'ie7');
	$ie6_url = elgg_get_simplecache_url('css', 'ie6');
?>
	<!--[if gt IE 7]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie_url; ?>" />
	<![endif]-->
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie7_url; ?>" />
	<![endif]-->
	<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie6_url; ?>" />
	<![endif]-->

<?php foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script['src']; ?>"></script>
<?php } ?>

<?php
echo $feedref;

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Extend page/elements/head instead", 1.8);
	echo $metatags;
}
?>
<script type="text/javascript">

window._taboola = window._taboola || [];

_taboola.push({article:'auto'});

!function (e, f, u) {

e.async = 1;

e.src = u;

f.parentNode.insertBefore(e, f);

}(document.createElement('script'),
document.getElementsByTagName('script')[0], 'https://cdn.taboola.com/libtrc/minds/loader.js');

</script>

