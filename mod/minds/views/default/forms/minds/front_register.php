<?php

echo elgg_view('input/text', array(	'name'=> 'u', 'placeholder'=>elgg_echo('username')));

echo elgg_view('input/text', array(	'name'=> 'e', 'placeholder'=>elgg_echo('email')));
 
echo elgg_view('input/submit', array('value' => elgg_echo('register:early'), 'class'=>'elgg-button-action'));
