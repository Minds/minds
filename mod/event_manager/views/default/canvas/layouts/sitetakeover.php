<?php

// Set the content type
header("Content-type: text/html; charset=UTF-8");
// get messages - try for errors first

$sysmessages = system_messages(null, "errors");
if (count($sysmessages["errors"]) == 0) {
	// no errors so grab rest of messages
	$sysmessages = system_messages(null, "");
} else {
	// we have errors - clear out remaining messages
	system_messages(null, "");
}

?>
<?php echo elgg_view('page_elements/header', $vars); ?>
<?php
if(elgg_is_admin_logged_in())
{
	echo elgg_view('page_elements/elgg_topbar', $vars);
}
?>
<?php echo elgg_view('page_elements/header_contents_takeover', $vars); ?>

<!-- main contents -->

<!-- display any system messages -->
<?php echo elgg_view('messages/list', array('object' => $sysmessages)); ?>


<!-- canvas -->
<div id="layout_canvas">
<?php echo $vars['area1']; ?>

<div class="clearfloat"></div>
</div><!-- /#layout_canvas -->

<!-- footer -->
<?php echo elgg_view('page_elements/footer_takeover', $vars); ?>