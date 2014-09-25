<?php

$class = $vars['class'];

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('page/elements/header', $vars);
$global_sidebar = elgg_view('page/elements/global_sidebar', $vars);
$body = elgg_view('page/elements/body', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html>
<html lang="en">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body class="<?php echo $class;?>">
	<?php echo $body; ?>
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>
