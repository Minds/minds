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

    private $indexes;
    private $db;
    private $conversation;
    private $participants = [];

    public function __construct($db = NULL, $indexes = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Entities');
        $this->indexes = $indexes ?: Di::_()->get('Database\Cassandra\Indexes');
    }

    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
        return $this;
    }

    public function getMessages($limit = 12, $offset = "", $finish = "")
    {
        
        $this->conversation->setGuid(null); //legacy messages get confused here
        $guid = $this->conversation->getGuid();
        
        $messages = $this->indexes->get("object:gathering:conversation:$guid", [
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
                $legacy_guids[$guid] = $json;
                continue;
            }
            $entities[$guid] = new Messenger\Entities\Message();
            $entities[$guid]->loadFromArray($message);
        }

        if($legacy_guids){
            $legacy_messages = $this->db->getRows($legacy_guids);
            foreach($legacy_messages as $guid => $message){
                $entities[$guid] = new Messenger\Entities\Message();
                $entities[$guid]->loadFromArray($message);
                $entities[$guid]->setMessages([ 
                    Session::getLoggedInUserGuid() => $message["message:".Session::getLoggedInUserGuid()]
                    ], true);
            }
        
        }

        return $entities;
    }



}
