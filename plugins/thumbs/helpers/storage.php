<?php
/**
 * Thumbs sotrage helpers
 */

namespace minds\plugin\thumbs\helpers;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Security;
use Minds\Core\entities;
use Minds\Helpers;
use Minds\Entities\Factory as EntitiesFactory;

class storage
{
    public static function insert($direction = 'up', $entity)
    {

        //check to see if we can interact with the entity
        if (!Security\ACL::_()->interact($entity)) {
            return false;
        }

        $db = new Data\Call('entities');
        $indexes = new Data\Call('entities_by_time');

        //quick and easy, direct insert to entity
        $db->insert($entity->guid, array("thumbs:$direction:count" => $entity->{"thumbs:$direction:count"} + 1));

        Helpers\Counters::increment($entity->guid, "thumbs:$direction");
        $cacher = Core\Data\cache\factory::build();
        $cacher->destroy("counter:$entity->guid:thumbs:$direction");

        //update if remind
        if ($entity->remind_object) {
            Helpers\Counters::increment($entity->remind_object['guid'], "thumbs:$direction");
            if (!$entity->remind_object['guid'] && $entity->remind_object['entity_guid']) {
                Helpers\Counters::increment($entity->remind_object['entity_guid'], "thumbs:$direction");
            }
            $cacher = Core\Data\cache\factory::build();
            $cacher->destroy("counter:".$entity->remind_object['guid'].":thumbs:$direction");
        }

        $user_guids = $entity->{"thumbs:$direction:user_guids"} ?: array();
        $user_guids[] = Core\Session::getLoggedInUserGuid();
        $db->insert($entity->guid, array("thumbs:$direction:user_guids" => json_encode($user_guids)));

        //now add to the entity list of thumbed up users
        $indexes->insert("thumbs:$direction:entity:$entity->guid", [ Core\Session::getLoggedInUserGuid() => time() ]);
        if ($entity->entity_guid) {
            $indexes->insert("thumbs:$direction:entity:$entity->entity_guid", [ Core\Session::getLoggedInUserGuid() => time() ]);
        }

        //now add to the users list of thumbed up content
        $indexes->insert("thumbs:$direction:user:".elgg_get_logged_in_user_guid(), array($entity->guid => time()));
        $indexes->insert("thumbs:$direction:user:".elgg_get_logged_in_user_guid() .":$entity->type", array($entity->guid => time()));

        if (in_array($entity->subtype, array('video', 'image')) || ($entity->type == 'activity' && $entity->custom_data)) {
            $prepared = new Core\Data\Neo4j\Prepared\Common();
            $subtype = $entity->subtype;
            $guid = $entity->guid;
            if ($entity->custom_type == 'video') {
                $subtype = 'video';
                $guid = $entity->custom_data['guid'];
                Helpers\Counters::increment($guid, "thumbs:$direction");
            } elseif ($entity->custom_type == 'batch') {
                $subtype = 'image';
                $guid = $entity->entity_guid;
                Helpers\Counters::increment($guid, "thumbs:$direction");
            }
            $cacher->destroy("counter:$guid:thumbs:$direction");
            if ($direction == 'up') {
                Core\Data\Client::build('Neo4j')->request($prepared->createVoteUP($guid, $subtype));
            } elseif ($direction == 'down') {
                Core\Data\Client::build('Neo4j')->request($prepared->createVoteDOWN($guid, $subtype));
            }
        } elseif ($entity->entity_guid) {
            Helpers\Counters::increment($entity->entity_guid, "thumbs:$direction");
            $cacher = Core\Data\cache\factory::build();
            $cacher->destroy("counter:$entity->entity_guid:thumbs:$direction");
        } elseif ($entity->remind_object && isset($entity->remind_object['guid'])) {
            if ($entity->remind_object['entity_guid']) {
                $remind_guid = $entity->remind_object['entity_guid'];
            } else {
                $remind_guid = $entity->remind_object['guid'];
            }
            Helpers\Counters::increment($remind_guid, "thumbs:$direction");
            $cacher = Core\Data\cache\factory::build();
            $cacher->destroy("counter:$remind_guid:thumbs:$direction");
        }

        if ($entity->owner_guid != Core\Session::getLoggedinUser()->guid) {
            if ($direction == 'up') {
                Core\Events\Dispatcher::trigger('notification', 'thumbs', [
                'to' => [ $entity->owner_guid ],
                'notification_view' =>'like',
                'title' => $entity->title,
                'entity' => $entity
              ]);
            } elseif ($direction == 'down') {
                Core\Events\Dispatcher::trigger('notification', 'thumbs', [
                'to' => [ $entity->owner_guid ],
                'notification_view' => 'downvote',
                'title' => $entity->title,
                'entity' => $entity
              ]);
            }
        }
    }

    public static function cancel($direction = 'up', $entity)
    {
        $db = new Data\Call('entities');
        $indexes = new Data\Call('entities_by_time');


        $db->insert($entity->guid, array("thumbs:$direction:count" => $entity->{"thumbs:$direction:count"} - 1));
        Helpers\Counters::increment($entity->guid, "thumbs:$direction", -1);
        if ($entity->type == 'activity' && $entity->entity_guid) {
            Helpers\Counters::increment($entity->entity_guid, "thumbs:$direction", -1);
        }
        $cacher = Core\Data\cache\factory::build();
        $cacher->destroy("counter:$entity->guid:thumbs:$direction");

        if ($entity->remind_object) {
            Helpers\Counters::increment($entity->remind_object['guid'], "thumbs:$direction", -1);
            if ($entity->remind_object['entity_guid']) {
                Helpers\Counters::increment($entity->remind_object['entity_guid'], "thumbs:$direction", -1);
            }
        }


        $user_guids = $entity->{"thumbs:$direction:user_guids"} ? : array();
        $user_guids = array_diff($user_guids, array(elgg_get_logged_in_user_guid()));
        $db->insert($entity->guid, array("thumbs:$direction:user_guids" => json_encode($user_guids)));

        //now remove from the entities list of thumbed up users
        $indexes->removeAttributes("thumbs:$direction:entity:$entity->guid", array(elgg_get_logged_in_user_guid()));

        //now remove from the users list of thumbs up content
        $indexes->removeAttributes("thumbs:$direction:user:" . elgg_get_logged_in_user_guid(), array($entity->guid));
        $indexes->removeAttributes("thumbs:$direction:user:" . elgg_get_logged_in_user_guid() .":$entity->type", array($entity->guid));
    }
}
