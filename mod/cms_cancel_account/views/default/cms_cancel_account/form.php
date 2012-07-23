<?php	 
	$form_body = "<p><label>" . elgg_echo('cms_cancel_account:askreason') . "<br /> <textarea name='reason' 'class='general-textarea' style='width:98%; height:200px;'/></textarea> </label><br />";
	 
	$form_body .= elgg_view('input/hidden', array('name' => 'action', 'value' => 'cms_cancel_account/request'));
	 
	$form_body .= elgg_echo("<br>");
	
	$form_body .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('cms_cancel_account:button:request'))) . "</p>";
	$form_body	= "<div style=\"width:400px\">$form_body</div>";
?>
<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/cms_cancel_account/request", 'body' => $form_body)) ?>