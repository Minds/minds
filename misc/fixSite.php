<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$site = elgg_get_site_entity();

$site->url = 'http://mehmac.local/';
$site->save();
