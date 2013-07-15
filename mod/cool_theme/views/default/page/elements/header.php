<?php

$site = elgg_get_site_entity();

echo "<h1 id=\"facebook-header-logo\">";
echo elgg_view('output/url', array(
	'href' => '/',
	'text' => $site->name,
));
echo "</h1>";

echo elgg_view_form('login', array('id' => 'facebook-header-login'));