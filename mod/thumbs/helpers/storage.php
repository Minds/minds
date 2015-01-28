<?php
/**
 * Thumbs sotrage helpers
 */
 
namespace minds\plugin\thumbs\helpers;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\entities;

class storage{


    public static function insert($direction = 'up', $entity){
        
        $db = new Data\Call('entities');
        $indexes = new Data\Call('entities_by_time');
        
        //quick and easy, direct insert to entity
        $db->insert($entity->guid, array("thumbs:$direction:count" => $entity->{"thumbs:$direction:count"} + 1));
        
        $user_guids = json_decode($entity->{"thumbs:$direction:user_guids"}, true) ?: array();
        $user_guids[] = elgg_get_logged_in_user_guid();
        $db->insert($entity->guid, array("thumbs:$direction:user_guids" => json_encode($user_guids)));
                
        //now add to the entity list of thumbed up users
        $indexes->insert("thumbs:$direction:entity:$entity->guid", array(elgg_get_logged_in_user_guid() => time()));
                
        //now add to the users list of thumbed up content
        $indexes->insert("thumbs:$direction:user:".elgg_get_logged_in_user_guid(), array($entity->guid => time()));
        $indexes->insert("thumbs:$direction:user:".elgg_get_logged_in_user_guid() .":$entity->type", array($entity->guid => time()));
        
        $prepared = new Core\Data\Neo4j\Prepared\Common();
        if($direction == 'up')
            Core\Data\Client::build('Neo4j')->request($prepared->createVoteUP($entity->guid, $entity->subtype));
        elseif($direction == 'down')
            Core\Data\Client::build('Neo4j')->request($prepared->createVoteDOWN($entity->guid, $entity->subtype));
    }

    public static function cancel($direction = 'up', $entity){
        
        $db = new Data\Call('entities');
        $indexes = new Data\Call('entities_by_time');
        
    
        $db->insert($entity->guid, array("thumbs:$direction:count" => $entity->{"thumbs:$direction:count"} - 1));
        
        
        $user_guids = json_decode($entity->{"thumbs:$direction:user_guids"}, true) ?: array();
        $user_guids = array_diff($user_guids, array(elgg_get_logged_in_user_guid()));
        $db->insert($entity->guid, array("thumbs:$direction:user_guids" => json_encode($user_guids)));
                
        //now remove from the entities list of thumbed up users
        $indexes->removeAttributes("thumbs:$direction:entity:$entity->guid", array(elgg_get_logged_in_user_guid()));
                
        //now remove from the users list of thumbs up content
        $indexes->removeAttributes("thumbs:$direction:user:" . elgg_get_logged_in_user_guid(), array($entity->guid));
        $indexes->removeAttributes("thumbs:$direction:user:" . elgg_get_logged_in_user_guid() .":$entity->type", array($entity->guid));
    }
	
}
