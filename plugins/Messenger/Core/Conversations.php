<?php
/**
 * Minds messenger conversations
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra;
use Minds\Core\Session;
use Minds\Entities\User;
use Minds\Plugin\Messenger;

class Conversations
{

    private $db;
    private $indexDb;
    private $redis;
    private $user;
    private $toUpgrade = [];

    public function __construct($db = NULL, $indexDb = NULL, $redis = NULL, $cache = NULL, $config = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->indexDb = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->redis = $redis ?: new \Redis();
        $this->config = $config ?: Di::_()->get('Config');
        $this->cache = $cache ?: new ConversationsCache($this->redis, $this->config);
        $this->user = Session::getLoggedinUser();
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getList($limit = 12, $offset = 0)
    {

        $conversations = [];

        $prepared = new Cassandra\Prepared\Custom();
        $prepared->query("SELECT * from entities_by_time WHERE key=:row LIMIT :size", [
          'row' => "object:gathering:conversations:{$this->user->guid}",
          'size' => 10000
        ]);

        //check cache for ids to return
        if(!$offset){
            $guids = $this->cache->setUser($this->user)->getGuids();
            if($guids && is_array($guids)){
                $prepared->query("SELECT * from entities_by_time WHERE key=:row AND column1 IN :guids LIMIT :size", [
                  'row' => "object:gathering:conversations:{$this->user->guid}",
                  'guids' => $guids,
                  'size' => 1000
                ]);
            }
        }

        $result = (array) $this->db->request($prepared);
        foreach($result as $item){
            $key = $item['column1'];
            $conversations[$key] = $item['value'];
        }

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

                $conversation = new Messenger\Entities\Conversation($this->indexDb);
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
        if(!$offset){
            $this->cache->setUser($this->user)->saveList($return);
        }
        return $return;
    }

    public function filterOnline($conversations)
    {
        try{
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
        } catch(\Exception $e){}

        return $conversations;
    }

    public function runUpgrades()
    {
        foreach($this->toUpgrade as $guid => $conversation){
            $conversation->saveToLists();
            $this->indexDb->remove("object:gathering:conversations:{$this->user->guid}", [ $guid ]);
        }
    }

}
