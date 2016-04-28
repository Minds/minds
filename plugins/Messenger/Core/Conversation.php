<?php
/**
 * Minds messenger conversation
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities\User;

class Conversation
{

    private $db;
    private $participants = [];

    public function __construct($db = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
    }

    public function setParticipant($user)
    {
        if(!$user instanceof User){
            throw new \Exception('Participant must be a user entity');
        }
        if(!isset($this->participants[$user->guid])){
            $this->participants[$user->guid] = $user;
        }
        return $this;
    }

    public function getMessages($limit = 12, $offset = "", $finish = "")
    {

        $key = $this->getIndexKeys();

        $messages = $this->db->get("object:gathering:conversation:$key", [
          'limit' => $limit,
          'offset'=> $offset,
          'finish'=> $finish,
          'reversed'=>true
        ]);

        $entities = [];

        foreach($messages as $guid => $json){
            $message = json_decode($json, true);
            if(!is_array($message)){
                //@todo polyfill for legacy messages (new messages are now denomalized)
                continue;
            }
            $entities[$guid] = new Messenger\Entities\Message();
            $entities[$guid]->loadFromArray($message);
        }

        return $entities;
    }

    public function getIndexKey()
    {
        return $this->permutateIndexKey($this->participants);
    }

    private function permutateIndexKey($input = [])
    {

        $result = "";
        ksort($input);
        foreach($input as $key => $item){
            $result .= $result ? ":$key" : $key;
        }

        return $result;
    }


}
