<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$db = new Minds\Core\Data\Call('entities_by_time');

$items = $db->getRow('activity', array('limit'=>1000));

echo count($items);

foreach($items as $item => $data){

    error_log("Adding  $activity->guid to the queue");

    $activity = new Minds\entities\activity($item);
    \Minds\Core\Queue\Client::build()->setExchange("mindsqueue")
                                ->setQueue("FeedDispatcher")
                                ->send(array(
                                    "guid" => $activity->guid,
                                    "owner_guid" => $activity->owner_guid
                                ));

    error_log("... added \n");
}

echo "Done \n";
