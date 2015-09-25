<?php
namespace Minds\Core\Boost;
use Minds\interfaces\BoostHandlerInterface;
use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;

/**
 * Newsfeed Boost handler
 */
class Newsfeed implements BoostHandlerInterface{

    private $db;

    public function __construct($options = array(), Data\Interfaces\ClientInterface $db = NULL){
        if($db){
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
    public function boost($entity, $impressions){
        if(is_object($entity)){
            $guid = $entity->guid;
        } else {
            $guid = $entity;
        }
        return $this->db->insert("boost", array('guid'=>$guid, 'owner_guid'=>Core\Session::getLoggedinUser()->guid, 'impressions'=>$impressions, 'state' => 'review', 'type'=> 'newsfeed'));
    }

     /**
     * Return boosts for review
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = ""){
        $query = array('state'=>'review', 'type'=>'newsfeed');
        if($offset){
            $query['_id'] = array('$gt'=>$offset);
        }
        $boosts = $this->db->find("boost", $query);
        if($boosts){
            $boosts->limit($limit);
            $boosts->sort(array('_id'=> 1));
        }
        return $boosts;
    }

    /**
     * Return the review count
     * @return int
     */
    public function getReviewQueueCount(){
        $query = array('state'=>'review', 'type'=>'newsfeed');
        $count = $this->db->count("boost", $query);
        return $count;
    }

    /**
     * Accept a boost
     * @param mixed $_id
     * @param int impressions
     * @return boolean
     */
    public function accept($_id, $impressions = 0){
        $boost_data= $this->db->find("boost", array('_id' => $_id));
        $boost_data->next();
        $boost = $boost_data->current();
        $accept = $this->db->update("boost", array('_id' => $_id), array('state'=>'approved'));
        if($accept){
            //remove from review
            //$db->removeAttributes("boost:newsfeed:review", array($guid));
            //clear the counter for boost_impressions
            //Helpers\Counters::clear($guid, "boost_impressions");

            $entity = new \Minds\entities\activity($boost['guid']);
            Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($entity->owner_guid),
                'object_guid' => $entity->guid,
                'from'=> 100000000000000519,
                'object_guid' => $entity->guid,
                'title' => $entity->title,
                'notification_view' => 'boost_accepted',
                'params' => array('impressions'=>$boost['impressions']),
                'impressions' => $boost['impressions']
                ));
        }
        return $accept;
    }

    /**
     * Reject a boost
     * @param mixed $_id
     * @return boolean
     */
    public function reject($_id){

        $boost_data= $this->db->find("boost", array('_id' => $_id));
        $boost_data->next();
        $boost = $boost_data->current();

        $this->db->remove("boost", array('_id'=>$_id));

        $entity = new \Minds\entities\activity($boost['guid']);
        Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
            'to'=>array($entity->owner_guid),
            'from'=> 100000000000000519,
            'object_guid' => $entity->guid,
            'title' => $entity->title,
            'notification_view' => 'boost_rejected',
            ));
        return true;//need to double check somehow..
    }

    /**
     * Return a boost
     * @return array
     */
    public function getBoost($offset = ""){
        $boosts = $this->getBoosts(1);
        return $boosts[0];
    }

    public function getBoosts($limit = 2){
        $cacher = Core\Data\cache\factory::build('apcu');
        $mem_log =  $cacher->get(Core\Session::getLoggedinUser()->guid . ":seenboosts") ?: array();

        $boosts = $this->db->find("boost", array('type'=>'newsfeed', 'state'=>'approved'));

        if(!$boosts){
            return null;
        }
        $boosts->sort(array('_id'=> 1));
        $boosts->limit(15);
        $return = array();
        foreach($boosts as $boost){
            if(count($return) >= $limit){
                break;
            }
            if(in_array((string)$boost['_id'], $mem_log)){
                continue; // already seen
            }

            $impressions = $boost['impressions'];
            //increment impression counter
            Helpers\Counters::increment((string) $boost['_id'], "boost_impressions", 1);
            //get the current impressions count for this boost
            Helpers\Counters::increment(0, "boost_impressions", 1);
            $count = Helpers\Counters::get((string) $boost['_id'], "boost_impressions", false);

            if($count > $impressions){
                //remove from boost queue
                $this->db->remove("boost", array('_id' => $boost['_id']));
                $entity = new \Minds\entities\activity($boost['guid']);
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($entity->owner_guid),
                'from'=> 100000000000000519,
                'object_guid' => $entity->guid,
                'title' => $entity->title,
                'notification_view' => 'boost_completed',
                'params' => array('impressions'=>$boost['impressions']),
                'impressions' => $boost['impressions']
                ));
                continue; //max count met
            }
            array_push($mem_log, (string) $boost['_id']);
            $cacher->set(Core\Session::getLoggedinUser()->guid . ":seenboosts", $mem_log, (12 * 3600));
            $return[] = $boost;
        }
        return $return;
    }

}
