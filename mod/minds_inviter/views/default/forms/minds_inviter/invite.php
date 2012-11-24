<?php
	$contacts = $vars['contacts'];
	
	foreach($contacts as $contact){
		$name = $contact['name'];
		$options[$name] = $contact['email'];
	}
?>
 
<div>
	<label><?php echo elgg_echo('minds_inviter:invite'); ?></label><br />
	<?php echo elgg_view('input/checkboxes', array('name'=>'emails','options' => $options)); ?>
</div>

<div class="elgg-foot">

<?php 

echo elgg_view('input/hidden', array('name'=>'user_guid','value' => elgg_get_logged_in_user_guid()));

echo elgg_view('input/submit', array('value' => elgg_echo('minds_inviter:invite')));

?>

</div>
