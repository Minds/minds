<?php
/**
 * Two factor setup
 */
$user = elgg_get_logged_in_user_entity();

if($user->twofactor){
	echo elgg_view('input/submit', array('name'=>'disable', 'value'=>'Disable', 'class'=>'delete'));
} else {
	echo "<label>You SMS number</label>";
	echo elgg_view('input/text', array('name'=>'tel', 'value'=>$user->telno, 'placeholder'=>'eg. +4407999000909'));
	echo elgg_view('input/submit');
}
