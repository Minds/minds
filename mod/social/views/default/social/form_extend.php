<?php

$services =  minds\plugin\social\start::$services;
$configured = minds\plugin\social\start::userConfiguredServices();

echo "<div id=\"social-selection\">";

foreach($services as $service){
	if(in_array($service, $configured)){
		echo elgg_view('input/checkbox', array('name'=>"social_triggers[$service]", 'value'=>'selected', 'id'=>$service));
		echo "<label for=\"$service\" class=\"entypo\">" . elgg_echo("icon:$service") . "</label>";
	} else {
		try{
			$url = minds\plugin\social\services\build::build($service)->authorizeURL();
			echo elgg_view('output/url', array('href'=>$url, 'text'=>$service, 'id'=>'social-'.$service, 'class'=>'social-popup'));
		} catch(\Exception $e){
			error_log($e->getMessage());
		}
	}
}

echo "</div>";
