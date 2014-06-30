<?php

elgg_load_js('jquery.autosize');
$input_text = elgg_view('input/plaintext', array(
    'name' => 'comment',
    'class' => 'comments-input',
    'placeholder' => 'Enter your comment here...'
));
		
if(elgg_is_logged_in()){
	$user = elgg_get_logged_in_user_entity();
} else {
	$user = get_user_by_username('minds');
}

if($user){
	$icon = elgg_view_entity_icon($user, 'tiny');
}

$form_body .= elgg_view_image_block($icon, $input_text);

$form_body .= elgg_view('input/submit', array(
    'value' => 'submit',
    'class' => 'hidden'
        ));

$form = elgg_view('input/form', array(
    'body' => $form_body,
    'enctype' => 'application/json',
    'action' => 'comments/entity/'.$vars['parent_guid'],
    'class' => 'minds-comments-form'
        ));

echo $form;

elgg_load_js('lightbox');
elgg_load_css('lightbox');

echo <<<HTML
<div id="comments-signup" style="display:none;">
	<p> You need a minds account in order to comment </p>
	<form id="login" action="action/login">
		<b>Login</b>
		<input type="text" name="u" placeholder="username" value="$u" autocomplete="off"/>
		<input type="password" name="p" value="$p" placeholder="password" autocomplete="off"/>
		<input type="submit" value="Login" class="elgg-button elgg-button-submit"/>
	</form>
	<form id="signup" action="action/register">
			<b>Signup</b>
			<input type="text" name="u" placeholder="username" value="$u" autocomplete="off"/>
			<input type="text" name="e" placeholder="email" value="$e" autocomplete="off"/>
			<input type="password" name="p" value="$p" placeholder="password" autocomplete="off"/>
			<input type="hidden" name="tcs" value="true"/>
			<input type="submit" value="Sign up" class="elgg-button elgg-button-submit"/>
	</form>
</div>
HTML;

