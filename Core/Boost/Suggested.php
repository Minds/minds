<?php

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use Minds\Interfaces;

/**
 * Suggested boost handler
 */
class Suggested implements Interfaces\BoostHandlerInterface
{
    private $db;

    public function __construct($options = array(), Data\Interfaces\ClientInterface $db = null)
    {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = Data\Client::build('MongoDB');
        }
    }

   /**
     * Boost an entity
     * @param object/int $entity - the entity to boost
     * @param int $impressions
     * @return boolean
     */
    public function boost($entity, $impressions)
    {
        if (is_object($entity)) {
            $guid = $entity->guid;
        } else {
            $guid = $entity;
        }
        return $this->db->insert("boost", array('guid'=>$guid, 'impressions'=>$impressions, 'state' => 'review', 'type'=> 'suggested'));
    }

     /**
     * Return boosts for review
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = "")
    {
        $query = array('state'=>'review', 'type'=>'suggested');
        if ($offset) {
            $query['_id'] = array('$gt'=>$offset);
        }
        $boosts = $this->db->find("boost", $query);
        if ($boosts) {
            $boosts->limit($limit);
        }
        return $boosts;
    }

    /**
     * Return the review count
     * @return int
     */
    public function getReviewQueueCount()
    {
        $query = array('state'=>'review', 'type'=>'suggested');
        $count = $this->db->count("boost", $query);
        return $count;
    }

    /**
     * Accept a boost
     * @param object/int $entity
     * @param int impressions
     * @return boolean
     */
    public function accept($_id, $impressions = 0)
    {
        $boost_data= $this->db->find("boost", array('_id' => $_id));
        $boost_data->next();
        $boost = $boost_data->current();
        $accept = $this->db->update("boost", array('_id' => $_id), array('state'=>'approved'));
        if ($accept) {
            $entity = \Minds\Entities\Factory::build($boost['guid']);
            if ($entity) {
                $to_guid = $entity->type == 'user' ? $entity->guid : $entity->owner_guid;
                Core\Events\Dispatcher::trigger('notification', 'boost', array(
                    'to'=>array($to_guid),
                    'entity' => $entity,
                    'from'=> 100000000000000519,
                    'title' => $entity->title,
                    'notification_view' => 'boost_accepted',
                    'params' => array('impressions'=>$boost['impressions']),
                    'impressions' => $boost['impressions']
                ));
                error_log('notification should have been sent to ' . $entity->guid);
            }
        }
        return $accept;
    }

    /**
     * Reject a boost
     * @param object/int $entity
     * @return boolean
     */
    public function reject($_id)
    {
        $boost_data= $this->db->find("boost", array('_id' => $_id));
        $boost_data->next();
        $boost = $boost_data->current();

        $this->db->remove("boost", array('_id'=>$_id));

        $entity = \Minds\Entities\Factory::build($boost['guid']);
        if ($entity) {
            $to_guid = $entity->type == 'user' ? $entity->guid : $entity->owner_guid;
            Core\Events\Dispatcher::trigger('notification', 'boost', array(
                'to'=>array($to_guid),
                'entity' => $entity,
                'from'=> 100000000000000519,
                'title' => $entity->title,
                'notification_view' => 'boost_rejected',
                ));
        }
        return true;//need to double check somehow..
    }

    /**
     * Return a boost
     * @return array
     */
    public function getBoost($offset = "")
    {
        $cacher = Core\Data\cache\factory::build();

        $boosts = $this->db->find("boost", array('type'=>'suggested', 'state'=>'approved'));
        if (!$boosts) {
            return null;
        }
        $boosts->limit(15);

        $boost_guids = array();
        foreach ($boosts as $boost) {
            $boost_guids[] = $boost['guid'];
        }

        $prepared = new Data\Neo4j\Prepared\Common();
        $result= Data\Client::build('Neo4j')->request($prepared->getActed($boost_guids));
        $rows = $result->getRows();

        foreach ($boosts as $boost) {
            $seen = false;
            if ($rows) {
                foreach ($rows['items'] as $item) {
                    if ($item['guid'] == $boost['guid']) {
                        $seen = true;
                    }
                }
            }
            if ($seen) {
                continue;
            }

            //get the current impressions count for this boost
            $count = Helpers\Counters::get($boost['guid'], "boost_swipes", false);
            if ($count > $boost['impressions']) {
                //remove from boost queue
                $this->db->remove("boost", array('_id' => $boost['_id']));
                $entity = \Minds\Entities\Factory::build($boost['guid']);
                Core\Events\Dispatcher::trigger('notification', 'boost', array(
                'to'=>array($entity->owner_guid),
                'from' => 100000000000000519,
                'entity' => $entity,
                'title' => $entity->title,
                'notification_view' => 'boost_completed',
                'params' => array('impressions'=>$boost['impressions']),
                'impressions' => $boost['impressions']
                ));
                continue; //max count met
            }
            return $boost;
        }
    }
}
