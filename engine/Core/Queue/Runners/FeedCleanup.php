<?php
namespace Minds\Core\Queue\Runners;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Data;
use Minds\Entities;
use Surge;

/**
 * Removes entities from multiple feeds, in the background
 */

class FeedCleanup implements Interfaces\QueueRunner{

   public function run(){
       $client = Queue\Client::Build();
       $client->setExchange("mindsqueue", "direct")
               ->setQueue("FeedCleanup")
               ->receive(function($data){
                   echo "Received a feed cleanup request \n";

                   $data = $data->getData();
                   $keyspace = $data['keyspace'];
                   $type = $data['type'];
                   $subtype = isset($data['subtype']) ? $data['subtype'] : '';
                   $super_subtype = isset($data['super_subtype']) ? $data['super_subtype'] : '';

                   $db = new Data\Call('entities_by_time', $keyspace);
                   $fof = new Data\Call('friendsof', $keyspace);
                   $offset = "";
                   while(true){
                        $guids = $fof->getRow($data['owner_guid'], array('limit'=>2000, 'offset'=>$offset));
                        if(!$guids)
                            break;

                        $guids = array_keys($guids);
                        if($offset)
                            array_shift($guids);

                        if(!$guids)
                            break;

                        if($offset == $guids[0])
                            break;

                        $offset = end($guids);

                        $followers =$guids;
                        foreach($followers as $follower){
                            $db->removeAttributes("$type:network:$follower", array($data['guid']));
                            if($subtype)
                              $db->removeAttributes("$type:$subtype:network:$follower", array($data['guid']));
                            if($super_subtype)
                              $db->removeAttributes("$type:$super_subtype:network:$follower", array($data['guid']));
                        }
                   }

                   echo "Succesfully removed all feeds for " . $data['guid'] . " \n\n";
               });
   }

}
