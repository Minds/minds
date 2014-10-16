<?php 

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

//analytics_cron();
//trending_cron();

$timespans = array(
	'day', 
	'week', 
	'month', 
	'year',
	'entire'
);
foreach($timespans as $timespan){

	$trending = new MindsTrending(array('google'), array('timespan'=>$timespan));
	$trending->pull();

}
