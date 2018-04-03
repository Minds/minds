<?php
/**
 * Minds messenger messages
 */

namespace Minds\Core\Messenger;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities;
use Minds\Core\Messenger;
use Minds\Core\Security\ACL;

class Messages
{
    private $indexes;
    private $db;
    private $conversation;
    private $participants = [];
    private $acl;

    public function __construct(
        $db = null,
        $indexes = null,
        $acl = null
    )
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Entities');
        $this->indexes = $indexes ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->acl = $acl ?: ACL::_();
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

        $opts = [
          'limit' => $limit,
          'offset'=> $offset,
          'finish'=> $finish,
          'reversed'=> true
        ];

        $messages = $this->indexes->get("object:gathering:conversation:$guid", $opts) ?: [];

        $entities = [];

        foreach ($messages as $guid => $json) {
            $message = json_decode($json, true);
            $entity = new Entities\Message();
            $entity->loadFromArray($message);

            if ($this->acl->read($entity)) {
                $entities[$guid] = $entity;
            }
        }

        return $entities;
    }
}
