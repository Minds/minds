<?php

$cacher = \minds\core\data\cache\factory::build();

$key = "remind-".md5(get_input('url'));
$count = (int)$cacher->get($key);

if ($count >= 1000 ) {
    $count = sprintf('%0.1f', $count / 1000);
    echo $count . "K";
    
} else {
    echo $count;
}