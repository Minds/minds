<?php
/**
 * overview block for user. extend to include subscribe links for example
 */
$user = $vars['entity'];
 
echo elgg_view('channel/subscribe', array('entity'=>$user));
