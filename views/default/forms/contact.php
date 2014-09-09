<?php

$user = elgg_get_logged_in_user_entity();

echo '<p> Contact </p>';
 
echo elgg_view('input/text', array(
	'name' => 'name',
	'class' => 'elgg-autofocus',
	'placeholder' => 'Your Name',
	'autocomplete' => 'off',
	'value' => elgg_is_logged_in() ? $user->name : ''
	));
	
echo elgg_view('input/text', array(
	'name' => 'email',
	'placeholder' => 'Your Email Address',
	'autocomplete' => 'off',
	'value' => elgg_is_logged_in() ? $user->email : ''
	));	
	
echo elgg_view('input/text', array(
	'name' => 'time',
	'class' => 'time',
	'placeholder' => 'What is the time?',
	'autocomplete' => 'off'
	));	
	
echo elgg_view('input/plaintext', array(
	'name' => 'message',
	'placeholder' => 'Enter your message here',
	'autocomplete' => 'off'
		));	

	
echo elgg_view('input/submit', array('value' => elgg_echo('Send'))); ?>
