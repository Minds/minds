<?php

global $DOMAIN;
$DOMAIN = 'www.word.am';

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');


$entities = elgg_get_entities(array('type'=>'object'));

var_dump($entities);
