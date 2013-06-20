<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if(empty($vars['entity']->color)){
    $color = "#FFFFFF";
}
else{
    $color = $vars['entity']->color;
}


echo elgg_echo("honeypot:color");
echo elgg_view('input/text', array(
                                'name' => 'params[color]', 
                                'value' => $color));
echo "<br />";
echo "<br />";

echo elgg_echo("honeypot:emailme");
echo elgg_view('input/dropdown', array(
                                'name' => 'params[emailme]', 
                                'value' => $vars['entity']->emailme,
                                'options_values' => array(
						'no' => "no",
						'yes' => "yes"
						)
    ));
echo "<br />";
echo "<br />";

echo elgg_echo("honeypot:emailaddress");
echo elgg_view('input/text', array(
                                'name' => 'params[emailaddress]', 
                                'value' => $vars['entity']->emailaddress));

echo "<br />";
echo "<br />";