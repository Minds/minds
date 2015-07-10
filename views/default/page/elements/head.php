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

$js = Minds\Core\resources::getLoaded('js','header');
$css = Minds\Core\resources::getLoaded('css','header');

$version = get_version();
$release = get_version(true);
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta property="al:android:url" content="minds://newsfeed">
    <meta property="al:android:app_name" name="al:android:app_name" content="Minds" data-app>
    <meta property="al:android:package" name="al:android:package" content="com.minds.mobile" data-app>
    <?php echo elgg_view('minds/meta');?>

	<?php echo elgg_view('page/elements/shortcut_icon', $vars); ?>

<?php foreach ($css as $item) {?>
	<link rel="stylesheet" href="<?php echo $item['src']; ?>" type="text/css" />
<?php } ?>
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,700italic|Indie+Flower' rel='stylesheet' type='text/css'>
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
