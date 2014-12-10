<?php

$lookup = new \minds\core\data\lookup('count:external:reminds');
    
$count = (int)$lookup->get(get_input('url'));


if ($count >= 1000 ) {
    $count = sprintf('%0.1f', $count / 1000);
    echo $count . "K";
    
} else {
    echo $count;
}