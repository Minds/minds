<?php

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	$enc = array();
	$file = elgg_get_site_url() . 'mod/westorElggMan/build/script/westorelggman.js';
	$zipFile = $file . '.gz';
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && file_exists($zipFile))
	{
	   $enc = explode(',', strtolower(preg_replace("/s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));
	   if (in_array('gzip', $enc))
	   {
	       header("Content-Encoding: gzip");
	       header("Content-Type: application/x-javascript");
	       echo file_get_contents($zipFile);
	       die;
	   }
	}
  header("Content-Type: application/x-javascript");
	echo file_get_contents($file);
	die;
?>