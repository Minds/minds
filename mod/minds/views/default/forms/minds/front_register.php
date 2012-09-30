<?php

echo elgg_view('input/text', array(	'name'=> 'n', 'placeholder'=>elgg_echo('name')));

echo elgg_view('input/text', array(	'name'=> 'u', 'placeholder'=>elgg_echo('username')));
 
echo elgg_view('input/submit', array('value' => elgg_echo('register:early')));
