<div>
<?php

$consumEnt = $vars['entity'];

if ($consumEnt->canEdit()) {

	$name = elgg_view('input/text', array('name' => 'name', 'value' => $consumEnt->name));
	
	$description = elgg_view('input/text', array('name' => 'desc', 'value' => $consumEnt->description));
	
	$callback = elgg_view('input/text', array('name' => 'callback', 'value' => $consumEnt->callbackUrl));
	
	$revA = '<input type="checkbox" name="reva" ' . ($consumEnt->revA ? 'checked="checked" ' : '') . '/>';

	$outbound = '<input type="checkbox" name="outbound" ' . ($consumEnt->consumer_type == 'outbound' ? 'checked="checked" ' : '') . '/>';

	$key = elgg_view('input/text', array('name' => 'key', 'value' => $consumEnt->key));
	
	$secret = elgg_view('input/text', array('name' => 'secret', 'value' => $consumEnt->secret));
	
	$guid = elgg_view('input/hidden', array('name' => 'guid', 'value' => $consumEnt->getGUID()));
	
	$submit = elgg_view('input/submit', array('name' => 'save', 'value' => elgg_echo('oauth:consumer:edit:submit')));

	$cancel = elgg_view('input/submit', array('name' => 'cancel', 'class' => 'cancel_button', 'value' => elgg_echo('oauth:consumer:edit:cancel')));
	
	$formbody = '<p><label>' . elgg_echo('oauth:register:name:label') . '</label>' . '<br />' . elgg_echo('oauth:register:name:desc') . $name . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('oauth:register:desc:label') . '</label>' . '<br />' . elgg_echo('oauth:register:desc:desc') . $description . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('oauth:register:callback:label') . '</label>' . '<br />' . elgg_echo('oauth:register:callback:desc') . $callback . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('oauth:register:reva:label') . '</label>' . '<br />' . $revA . ' ' . elgg_echo('oauth:register:reva:desc') . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('oauth:register:outbound:label') . '</label>' . '<br />' . $outbound . ' ' . elgg_echo('oauth:register:outbound:desc') . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('oauth:register:key:label') . '</label>' . '<br />' . elgg_echo('oauth:register:key:desc') . $key . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('oauth:register:secret:label') . '</label>' . '<br />' . elgg_echo('oauth:register:secret:desc') . $secret . "</p>\n";
	$formbody .= $guid . "\n";
	$formbody .= $submit . "\n";
	$formbody .= $cancel . "\n";

	$form = elgg_view('input/form', array('action' => $CONFIG->wwwroot . 'action/oauth/editconsumer', 
					      'body' => $formbody));

	echo $form;

} else {

	echo 'Permission denied';

}
?>
</div>
