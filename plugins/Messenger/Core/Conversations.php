<?php
/**
 * Minds messenger conversations
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities\User;
use Minds\Plugin\Messenger;

class Conversations
{

    private $db;
    private $redis;
    private $user;
    private $toUpgrade = [];

    public function __construct($db = NULL, $redis = NULL, $config = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->redis = $redis ?: new \Redis();
        $this->config = $config ?: Di::_()->get('Config');
        $this->user = Session::getLoggedinUser();
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getList($limit = 12, $offset = 0)
    {
        //@todo review for scalability. currently for pagination we need to load all conversation guids/time
        $conversations = $this->db->get("object:gathering:conversations:{$this->user->guid}", ['limit'=>10000]);
        if($conversations){
            $return = [];

            $i = 0;
            $ready = false;
            foreach($conversations as $guid => $data){
             
                if((string) $guid === (string) Session::getLoggedinUser()->guid)
                    continue;

                if($guid == $offset){
                    unset($conversations[$guid]);
                    continue;
                }

                if(is_numeric($data)){
                    $data = [
                      'ts' => $data,
                      'unread' => 0
                    ];
                } else {
                    $data = json_decode($data, true);
                }

                $conversation = new Messenger\Entities\Conversation($this->db);
                $conversation->loadFromArray($data);
                //$conversation->setGuid($guid);
                //$this->db->remove("object:gathering:conversations:{$this->user->guid}", ["100000000000000063:100000000000000599:100000000000000599", "100000000000000599:442275590062477312:442275590062477312"]);
                if(strpos($guid, ':') === FALSE){
                    $conversation->clearParticipants();
                    $conversation->setParticipant(Session::getLoggedinUser()->guid)
                        ->setParticipant($guid);
                    $this->toUpgrade[$guid] = $conversation;
                } else {
                    $conversation->setGuid($guid);
                }

                $return[] = $conversation;
                continue;
            }
        }
        usort($return, function($a, $b){
          return $b->ts - $a->ts;
        });
        
        $return = array_slice($return, (int) $offset, $limit);
        $return = $this->filterOnline($return);
        $this->runUpgrades();
        return $return;
    }

    public function filterOnline($conversations)
    {
        $config = $this->config->get('redis');
        $this->redis->connect($config['pubsub'] ?: $config['master'] ?: '127.0.0.1');
        //put this set of conversations into redis
        $guids = [];
        foreach($conversations as $conversation){
            foreach($conversation->getParticipants() as $participant){
                if($participant != Session::getLoggedInUserGuid())
                    $guids[$participant] = $participant;
            }
        }
        array_unshift($guids, Session::getLoggedInUserGuid() . ":conversations");
        call_user_func_array([$this->redis, 'sadd'], $guids);

        //return the online users
        $online = $this->redis->sinter("online", Session::getLoggedInUserGuid() . ":conversations");

        foreach($conversations as $key => $conversation){
            foreach($conversation->getParticipants() as $participant){
                if(in_array($participant, $online)){
                    $conversations[$key] = $conversation->setOnline(true);
                }
            }
        }

        return $conversations;
    }

    public function runUpgrades()
    {
        foreach($this->toUpgrade as $guid => $conversation){
            $conversation->saveToLists();
            $this->db->remove("object:gathering:conversations:{$this->user->guid}", [ $guid ]);
        }
    }

}
