<?php

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    // SSL connection
	echo 'ssl';
}
var_dump($_SERVER);
exit;
var_dump($_SERVER['X-Forwarded-Proto'], $_REQUEST['X-Forwarded-Proto'], $_SERVER['SERVER_PORT'],$_SERVER['HTTPS']);
