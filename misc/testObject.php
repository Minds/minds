<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$blogs = elgg_get_entities(array('type'=>'object', 'subtype'=>'blog', 'limit'=>12));

var_dump($blogs);
