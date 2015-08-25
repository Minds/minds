<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

//create a newspost

$activity = new Minds\entities\activity();
$activity->setMessage("Hello Minds!");
$guid = $activity->save();
        
Minds\Core\Boost\Factory::build('Newsfeed')->boost($guid, 1);
Minds\Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
        'to'=>array(Minds\Core\session::getLoggedinUser()->guid),
        'object_guid' => $guid,
        'notification_view' => 'boost_submitted',
        'params' => array('impressions'=>1),
        'impressions' => 1
        ));