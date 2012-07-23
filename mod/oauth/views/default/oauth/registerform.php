<div>
<div id="oauth_newregistration">
<?php

echo elgg_view_title(elgg_echo('oauth:register:title'));

$name = elgg_view('input/text', array('name' => 'name'));

$description = elgg_view('input/text', array('name' => 'desc'));

$callback = elgg_view('input/text', array('name' => 'callback'));

// this needs to be an elgg view
$revA = '<input type="checkbox" name="reva" checked="checked" />';

// this needs to be an elgg view
$outbound = '<input type="checkbox" name="outbound" />';

$key = elgg_view('input/text', array('name' => 'key'));

$secret = elgg_view('input/text', array('name' => 'secret'));

$submit = elgg_view('input/submit', array('value' => elgg_echo('oauth:register:submit')));

$formbody = '<p><label>' . elgg_echo('oauth:register:name:label') . '</label>' . '<br />' . elgg_echo('oauth:register:name:desc') . $name . "</p>\n";
$formbody .= '<p><label>' . elgg_echo('oauth:register:desc:label') . '</label>' . '<br />' . elgg_echo('oauth:register:desc:desc') . $description . "</p>\n";
$formbody .= '<p><label>' . elgg_echo('oauth:register:callback:label') . '</label>' . '<br />' . elgg_echo('oauth:register:callback:desc') . $callback . "</p>\n";
$formbody .= '<p><label>' . elgg_echo('oauth:register:reva:label') . '</label>' . '<br />' . $revA . ' ' . elgg_echo('oauth:register:reva:desc') . "</p>\n";
$formbody .= '<p><label>' . elgg_echo('oauth:register:outbound:label') . '</label>' . '<br />' . $outbound . ' ' . elgg_echo('oauth:register:outbound:desc') . "</p>\n";
$formbody .= '<p><label>' . elgg_echo('oauth:register:key:label') . '</label>' . '<br />' . elgg_echo('oauth:register:key:desc') . $key . "</p>\n";
$formbody .= '<p><label>' . elgg_echo('oauth:register:secret:label') . '</label>' . '<br />' . elgg_echo('oauth:register:secret:desc') . $secret . "</p>\n";
$formbody .= $submit;

$form = elgg_view('input/form', array('action' => $CONFIG->wwwroot . 'action/oauth/register', 
				      'body' => $formbody));

echo $form;

?>
</div>
<input id="oauth_shownewregistration" type="submit" value="<?php echo elgg_echo('oauth:register:show') ?>" />
<script type="text/javascript">
	$(document).ready(function() {
	  $("#oauth_newregistration").hide();
	});

	$("#oauth_shownewregistration").click(function(event) {
	  event.preventDefault();
	  $("#oauth_shownewregistration").slideUp("slow");
	  $("#oauth_newregistration").slideDown("slow");
	});

</script>
</div>