<?php
/**
 * Minds Inivter Handler page
 */
 
elgg_set_viewtype('popup');
 
$provider = get_input('provider');

$path = elgg_get_plugins_path() . 'minds_inviter/handlers/' . $provider . '.php';

if(file_exists($path)){
	include($path);
}