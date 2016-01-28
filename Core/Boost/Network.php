<?php
namespace Minds\Core\Boost;

use Minds\Interfaces\BoostHandlerInterface;
use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Helpers;

/**
 * Newsfeed Boost handler
 */
class Network implements BoostHandlerInterface
{

    protected $handler = 'newsfeed';
    protected $mongo;
    protected $db;

    protected $tries = 0;

    public function __construct($options = [], Data\Interfaces\ClientInterface $mongo = null, Data\Call $db = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
        $this->db = $db ?: new Data\Call('entities_by_time');
    }

    /**
     * Boost an entity
     * @param object/int $entity - the entity to boost
     * @param int $impressions
     * @return boolean
     */
    public function boost($boost, $impressions = 0)
    {

        $this->mongo->insert("boost", $data = [
          'guid' => $boost->getGuid(),
          'owner_guid' => $boost->getOwner()->guid,
          'impressions' => $boost->getBid(),
          'state' => 'review',
          'type' => $this->handler
        ]);

        if(isset($data['_id']))
            return (string) $data['_id'];

        return $boost->getGuid();

    }

     /**
     * Return boosts for review
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = "")
    {

        $query = [ 'state'=>'review', 'type'=> $this->handler ];
        if ($offset) {
            $query['_id'] = [ '$gt' => $offset ];
        }
        $queue = $this->mongo->find("boost", $query);
        $queue->limit($limit);
        $queue->sort(array('_id'=> 1));

        if (!$queue) {
          return false;
        }

        $guids = [];
        $end = "";
        foreach ($queue as $data) {
            $_id = (string) $data['_id'];
            $guids[$_id] = $data['guid'];
            $end = $data['guid'];
            //$this->mongo->remove("boost", ['_id' => $_id]);
        }

        if(!$guids){
          return false;
        }

        $db = new Data\Call('entities_by_time');
        $data = $db->getRow("boost:$this->handler", [
          'limit'=>$limit,
          'offset'=> $end,
          'reversed'=>true
        ]);

        $data = array_reverse($data); // oldest to newest

        $boosts = [];
        foreach ($data as $guid => $raw_data) {
            //$raw_data['guid']
            $boost = (new Entities\Boost\Network())
              ->loadFromArray(json_decode($raw_data, true));
            //double check
            if(isset($guids[$boost->getId()])){
                $boosts[] = $boost;
            }
        }
        return $boosts;
    }

    /**
     * Return the review count
     * @return int
     */
    public function getReviewQueueCount()
    {
        $query = ['state' => 'review', 'type' => $this->handler];
        $count = $this->mongo->count("boost", $query);
        return $count;
    }

    /**
     * Get our own submitted Boosts
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getOutbox($limit, $offset = "")
    {
        $db = new Data\Call('entities_by_time');
        $data = $db->getRow("boost:$this->handler:requested:" . Core\Session::getLoggedinUser()->guid, [
          'limit'=>$limit,
          'offset'=>$offset,
          'reversed'=>true
        ]);

        $boosts = [];
        foreach ($data as $guid => $raw_data) {
            //$raw_data['guid']
            $boosts[] = (new Entities\Boost\Network())
              ->loadFromArray(json_decode($raw_data, true));
        }
        return $boosts;
    }

    public function getBoostEntity($guid)
    {
        $db = new Data\Call('entities_by_time');
        $data = $db->getRow("boost:$this->handler", ['limit'=>1, 'offset'=>$guid]);
        if (key($data) != $guid) {
            return false;
        }

        $boost = (new Entities\Boost\Network($db))
          ->loadFromArray(json_decode($data[$guid], true));
        return $boost;
    }

    /**
     * Accept a boost
     * @param mixed $_id
     * @param int impressions
     * @return boolean
     */
    public function accept($boost, $impressions = 0)
    {

        $accept = $this->mongo->update("boost", ['_id' => $boost->getId()], ['state'=>'approved']);
        $boost->setState('approved');
        if ($accept) {
            //remove from review
            //$db->removeAttributes("boost:newsfeed:review", array($guid));
            //clear the counter for boost_impressions
            //Helpers\Counters::clear($guid, "boost_impressions");

            Core\Events\Dispatcher::trigger('notification', 'boost', [
                'to'=> [ $boost->getOwner()->guid ],
                'entity' => $boost->getEntity(),
                'from'=> 100000000000000519,
                'title' => $boost->getEntity()->title,
                'notification_view' => 'boost_accepted',
                'params' => ['impressions' => $boost->getBid()],
                'impressions' => $boost->getBid()
              ]);
            $boost->save();
        }
        return $accept;
    }

    /**
     * Reject a boost
     * @param mixed $_id
     * @return boolean
     */
    public function reject($boost)
    {
        $this->mongo->remove("boost", ['_id'=>$boost->getId()]);
        $boost->setState('rejected')
          ->save();

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to'=> [ $boost->getOwner()->guid ],
            'from'=> 100000000000000519,
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_rejected',
        ]);
        return true;//need to double check somehow..
    }

    /**
     * Return a boost
     * @return array
     */
    public function getBoost($offset = "")
    {
        $boosts = $this->getBoosts(1);
        return $boosts[0];
    }

    /**
     * Expire a boost from the queue
     * @param Object $boost
     * @return void
     */
    private function expireBoost($boost)
    {
        if(!$boost){
            return;
        }

        $boost->setState('completed')
          ->save();

        $this->mongo->remove("boost", [ '_id' => $boost->getId() ]);

        Core\Events\Dispatcher::trigger('notification', 'boost', [
          'to'=> [ $boost->getOwner()->guid ],
          'from'=> 100000000000000519,
          'entity' => $boost->getEntity(),
          'title' => $boost->getEntity()->title,
          'notification_view' => 'boost_completed',
          'params' => ['impressions' => $boost->getBid()],
          'impressions' => $boost->getBid()
        ]);
    }

    /**
     * Excuse the horrible mess here!!
     * (sorry!)
     * @return array
     */
    private function patchThumbs($boosts)
    {
        $keys = [];
        foreach($boosts as $boost){
            $keys[] = "thumbs:up:entity:$boost->guid";
        }
        $thumbs = $this->db->getRows($keys, ['offset'=> Core\Session::getLoggedInUserGuid()]);
        foreach($boosts as $k => $boost){
            $key = "thumbs:up:entity:$boost->guid";
            if(isset($thumbs[$key])){
                $boosts[$k]->{'thumbs:up:user_guids'} = array_keys($thumbs[$key]);
            }
        }
        return $boosts;
    }

    public function getBoosts($limit = 2, $increment = true)
    {
        $cacher = Core\Data\cache\factory::build('apcu');
        $mem_log =  $cacher->get(Core\Session::getLoggedinUser()->guid . ":seenboosts:$this->handler") ?: [];

        $opts = [
            'type'=>$this->handler,
            'state'=>'approved',
        ];
        if($mem_log){
            $opts['_id'] =  [ '$gt' => end($mem_log) ];
        }

        $boosts = $this->mongo->find("boost", $opts);

        if (!$boosts) {
            return null;
        }
        $boosts->sort(['_id'=> 1]);
        $boosts->limit(50);
        $return = [];
        foreach ($boosts as $data) {
            if (count($return) >= $limit) {
                break;
            }
            if (in_array((string) $data['_id'], $mem_log)) {
                continue; // already seen
            }

            $impressions = $data['impressions'];
            if($increment){
                //increment impression counter
                Helpers\Counters::increment((string) $data['_id'], "boost_impressions", 1);
                //get the current impressions count for this boost
                Helpers\Counters::increment(0, "boost_impressions", 1);
            }
            $count = Helpers\Counters::get((string) $data['_id'], "boost_impressions", false);

            $boost = $this->getBoostEntity($data['guid']);
            $legacy_boost = false;

            if ($count > $impressions) {

                if($legacy_boost){
                    $this->mongo->remove("boost", ['_id' => $data['_id']]);
                /*    Core\Events\Dispatcher::trigger('notification', 'boost', [
                      'to'=>array($entity->owner_guid),
                      'from'=> 100000000000000519,
                      'entity' => $entity,
                      'title' => $entity->title,
                      'notification_view' => 'boost_completed',
                      'params' => array('impressions'=>$boost['impressions']),
                      'impressions' => $boost['impressions']
                      ]);*/
                } else {
                    $this->expireBoost($boost);
                }

                continue; //max count met
            }
            array_push($mem_log, (string) $data['_id']);
            $cacher->set(Core\Session::getLoggedinUser()->guid . ":seenboosts:$this->handler", $mem_log, (12 * 3600));

            if($legacy_boost){
                $return[] = $entity;
            } else {
                $return[$data['guid']] = $boost->getEntity();
            }
        }
        if(empty($return) && !empty($mem_log)){
            $cacher->destroy(Core\Session::getLoggedinUser()->guid . ":seenboosts:$this->handler");
            $this->tries++;
            if($this->tries > 2)
                return $this->getBoosts($limit, $increment);
        }
        $return = $this->patchThumbs($return);
        return $return;
    }
}
