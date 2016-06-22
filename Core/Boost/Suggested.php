<?php

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use Minds\Interfaces;

/**
 * LEGACY Suggested boost handler
 */
class Suggested extends Network implements Interfaces\BoostHandlerInterface
{
    protected $handler = 'suggested';
    protected $useNeo = true;

    public function useNeo($use = true)
    {
        $this->useNeo = $use;
        return $this;
    }


    /**
     * Return a boost
     * @return array
     */
    public function getBoost($offset = "")
    {
        $cacher = Core\Data\cache\factory::build();

        $boosts = $this->mongo->find("boost", [
            'type'=>'suggested',
            'state'=>'approved',
        ], [
            'limit' => 15,
        ]);
        if (!$boosts) {
            return null;
        }

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
                    $this->mongo->remove("boost", array('_id' => $boost['_id']));
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

    public function getBoosts($limit = 15, $increment = true)
    {
        $cacher = Core\Data\cache\factory::build();
        $mem_log =  $cacher->get(Core\Session::getLoggedinUser()->guid . ":seenboosts:suggested") ?: [];

        $opts = [
            'type'=>'suggested',
            'state'=>'approved',
        ];  
        if($mem_log){
            $opts['_id'] =  [ '$gt' => end($mem_log) ];
        }
        $boosts = $this->mongo->find("boost", $opts, [
            'limit' => 100,
        ]);
        if (!$boosts) {
            return null;
        }

        $return = [];
        foreach ($boosts as $boost) {
            if (count($return) >= $limit) {
                break;
            }
           
            if (in_array((string)$boost['_id'], $mem_log)) {
                continue; // already seen
            }
            Helpers\Counters::increment((string)$boost['_id'], "boost_impressions");
            $count = Helpers\Counters::get((string) $boost['_id'], "boost_impressions", false);
            $entity = \Minds\Entities\Factory::build($boost['guid']);
            if ($count > $boost['impressions'] || !$entity) {
                $this->mongo->remove("boost", array('_id' => $boost['_id']));

                Core\Events\Dispatcher::trigger('notification', 'boost', [
                    'to'=> [$entity->owner_guid],
                    'from' => 100000000000000519,
                    'entity' => $entity,
                    'title' => $entity->title,
                    'notification_view' => 'boost_completed',
                    'params' => ['impressions'=>$boost['impressions']],
                    'impressions' => $boost['impressions']
                ]);
                continue; //max count met
            }

            array_push($mem_log, (string) $boost['_id']);
            $cacher->set(Core\Session::getLoggedinUser()->guid . ":seenboosts:suggested", $mem_log, (12 * 3600));
            $return[] = $entity;
        }
        if (empty($return) && !empty($mem_log)) {
            $cacher->destroy(Core\Session::getLoggedinUser()->guid . ":seenboosts:suggested");
            return $this->getBoosts($limit);
        }
        return $return;
    }
}
