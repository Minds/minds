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

	$cacher = \minds\core\data\cache\factory::build();
        $hash = md5(elgg_get_site_url());
        $tspan = get_input('timespan', 'day');
        $cacher->destroy("$hash:trending-guids:12:0:$tspan");


}
