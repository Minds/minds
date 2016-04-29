<?php
/**
 * Minds messenger conversations
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities\User;

class Conversations
{

    private $db;
    private $user;

    public function __construct($db = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->user = Session::getLoggedinUser();
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getList($limit = 12, $offset = "")
    {
        //@todo review for scalability. currently for pagination we need to load all conversation guids/time
        $conversations = $this->db->get("object:gathering:conversations:{$this->user->guid}", ['limit'=>10000]);
        if($conversations){
            $return = [];

            arsort($conversations);
            $i = 0;
            $ready = false;
            foreach($conversations as $indexKey => $data){

                if(!$ready && $offset){
                    if($indexKey == $offset)
                        $ready = true;
                    continue;
                }

                if((string) $indexKey === (string) Session::getLoggedinUser()->guid)
                    continue;

                //if(($i++ > 12 && !$offset) || ($i++ > 24))
                //    continue;

                if($indexKey == $offset){
                    unset($conversations[$indexKey]);
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

                //future proofing for group chat
                foreach($data['participants'] as $k => $user_guid){
                    if($user_guid != Session::getLoggedinUser()->guid){
                        $user = new User($user_guid);
                        $data['participants'][$k] = $user->export();
                        $data['guid'] = (string) $user_guid; //for legacy support
                        $data['name'] = $user->name;
                        $data['username'] = $user->username;
                    } else {
                      unset($data['participants'][$k]);
                    }
                }
                $data['participants'] = array_values($data['participants']);

                $return[] = $data;
                continue;
            }
        }
        return $return;
    }

    public function buildConversation($users = [])
    {

    }



}
