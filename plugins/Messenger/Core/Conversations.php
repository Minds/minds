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
            foreach($conversations as $guid => $data){

                if(!$ready && $offset){
                    if($guid == $offset)
                        $ready = true;
                    continue;
                }

                if((string) $guid === (string) Session::getLoggedinUser()->guid)
                    continue;

                //if(($i++ > 12 && !$offset) || ($i++ > 24))
                //    continue;

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
                $data['guid'] = $guid;

                $conversation = new Messenger\Entities\Conversation();
                $conversation->loadFromArray($data);

                $return[] = $conversation;
                continue;
            }
        }
        return $return;
    }

}
