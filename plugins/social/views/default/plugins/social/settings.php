<?php

$services =  minds\plugin\social\start::$services;

foreach($services as $service){
	echo elgg_view('plugins/social/services/'.$service, $vars);
}
