<?php
/**
 * Minds Inivter Handler page
 */
 
$provider = get_input('provider');

$path = elgg_get_plugins_path() . 'minds_inviter/handlers/' . $provider . '.php';

if(file_exists($path)){
	include($path);
}

