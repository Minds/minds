<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Data;
use Minds\Entities;
use Surge;

/**
 * adds entities to multiple feeds, in the background
 */

class FeedDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("FeedDispatcher")
               ->receive(function ($data) {
                   echo "Received a feed dispatch request \n";

                   $data = $data->getData();
                   $keyspace = $data['keyspace'];

                   $entity = entities\Factory::build($data['guid']);

                   $db = new Data\Call('entities_by_time', $keyspace);
                   $fof = new Data\Call('friendsof', $keyspace);
                   $offset = "";
                   while (true) {
                       try {
                           $guids = $fof->getRow($entity->owner_guid, array('limit'=>500, 'offset'=>$offset));
                           if (!$guids) {
                               break;
                           }

                           $guids = array_keys($guids);
                           if ($offset) {
                               array_shift($guids);
                           }

                           if (!$guids) {
                               echo "No more for $entity->guid. Moving on \n";
                               break;
                           }

                           if ($offset == $guids[0]) {
                               break;
                           }

                           $offset = end($guids);

                           $followers = $guids;

                           $ninetyDays = (60 * 60 * 24 * 90);

                            foreach ($followers as $follower) {
                                $db->insert("$entity->type:network:$follower", 
                                    [ $entity->guid => $entity->guid ],
                                    $ninetyDays, //ttl
                                    true //async (silent)
                                );
                                if ($entity->subtype) {
                                    $db->insert("$entity->type:$entity->subtype:network:$follower",
                                        [ $entity->guid => $entity->guid ],
                                        $ninetyDays, //ttl
                                        true //async (silent)
                                    );
                               }
                           }

                           echo "First batch for $entity->guid dispatched. Loading next from $offset... \n";
                          //  var_dump($offset);
                            if ($offset == 0 || !$offset) {
                                echo "done..";
                                break;
                            }
                       } catch (\Exception $e) {
                           echo "Ooops... slight bump, there.. " . $e->getMessage() . " \n";
                       }
                   }

                   echo "Succesfully deployed all feeds ($entity->type, $entity->subtype, $entity->super_subtype) for $entity->owner_guid:$entity->guid \n\n";
               });
    }
}
