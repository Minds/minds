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
        $conversation_guids = $this->db->get("object:gathering:conversations:{$this->user->guid}", ['limit'=>10000]);
        if($conversation_guids){
            $conversations = [];

            arsort($conversation_guids);
            $i = 0;
            $ready = false;
            foreach($conversation_guids as $user_guid => $data){
                if(!$ready && $offset){
                    if($user_guid == $offset)
                        $ready = true;
                    continue;
                }
                if($i++ > 12 && !$offset)
                    continue;

                if($i++ > 24){
                    continue;
                }

                if($user_guid == $offset){
                    unset($conversation_guids[$user_guid]);
                    continue;
                }
              if(is_numeric($data)){
          $ts = $data;
          $unread = 0;
        } else {
          $data = json_decode($data, true);
          $unread = $data['unread'];
          $ts = $data['ts'];
        }
        $u = new User($user_guid);
        $u->last_msg = $ts;
        $u->unread = $unread;
        if($u->username && $u->guid != Session::getLoggedinUser()->guid){
          $conversations[] = $u;
        }
        continue;
      }

    }
    return $conversations;
    }

    public function buildConversation($users = [])
    {

    }



}
