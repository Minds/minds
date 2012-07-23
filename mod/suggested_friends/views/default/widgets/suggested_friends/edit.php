<?php

//
// select for friends  
echo elgg_echo('suggested_friends:friends:only') . "<br>";
$options = array(
  'name' => 'params[look_in_friends]',
  'value' => $vars['entity']->look_in_friends ? $vars['entity']->look_in_friends : "yes",
  'options_values' => array(
   	'yes' => elgg_echo('option:yes'),
   	'no' => elgg_echo('option:no'),
  ),
);
	
echo elgg_view('input/dropdown', $options) . "<br><br>";
	

//
// select for groups
echo elgg_echo('suggested_friends:groups:only') . "<br>";
$options['name'] = 'params[look_in_groups]';
$options['value'] = $vars['entity']->look_in_groups ? $vars['entity']->look_in_groups : "yes";

echo elgg_view('input/dropdown', $options) . "<br><br>";


//
// number of results
echo elgg_echo('suggested_friends:how:many') . "<br>";
$options['name'] = 'params[num_display]';
$options['value'] = $vars['entity']->num_display ? $vars['entity']->num_display : 2;
$options['options_values'] = array(1,2,3,4,5,6,7,8,9,10);

echo elgg_view('input/dropdown', $options);
