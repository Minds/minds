<?php
namespace Minds\Core\Queue\Runners;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Data;
use Minds\entities;
use Surge;

/**
 * Add entities to multiple feeds, in the background
 */

class FeedDispatcher implements Interfaces\QueueRunner{
    
   public function run(){
       $client = Queue\Client::Build();
       $client->setExchange("mindsqueue", "direct")
               ->setQueue("FeedDispatcher")
               ->receive(function($data){
                   echo "Received a feed dispatch request \n";
                   
                   $data = $data->getData();
                   
                   $entity = entities\Factory::build($data['guid']);
                   
                   $db = new Data\Call('entities_by_time');
                   $fof = new Data\Call('friendsof');
                   $offset = "";
                   while(true){
                        $guids = $fof->getRow($entity->owner_guid, array('limit'=>2000, 'offset'=>$offset));
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
                        
                        $followers = array_keys($guids);

                        foreach($followers as $follower)
                            $db->insert("$entity->type:network:$follower", array($entity->guid => $entity->guid));
                   }    
                  
                   echo "Succesfully deployed all feeds for $entity->guid \n\n";
               });
   }   
           
}   
