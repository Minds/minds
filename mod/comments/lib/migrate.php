<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/engine/start.php');

$es = new elasticsearch();
$es->index = $CONFIG->elasticsearch_prefix . 'comments';

$es->query('entity', NULL, 'time_created:desc', 500,0);