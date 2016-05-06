<?php
/**
 * Minds messenger messages
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities\User;

use Minds\Plugin\Messenger;

class Messages
{

    private $db;
    private $conversation;
    private $participants = [];

    public function __construct($db = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
    }

    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
        return $this;
    }

    public function getMessages($limit = 12, $offset = "", $finish = "")
    {

        $guid = $this->conversation->getGuid();

        $messages = $this->db->get("object:gathering:conversation:$guid", [
          'limit' => $limit,
          'offset'=> $offset,
          //'finish'=> $finish,
          'reversed'=>true
        ]) ?: [];

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



}
