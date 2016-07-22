<?php
/**
 * Messenger Message
 */

namespace Minds\Plugin\Messenger\Entities;

use Minds\Core\Session;
use Minds\Core\Di\Di;
use Minds\Core\Events;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\User;
use Minds\Plugin\Messenger;

class Message extends DenormalizedEntity
{
    protected $conversation;

    protected $exportableDefaults = [
            'guid', 'friendly_ts', 'message', 'messages', 'type', 'subtype', 'owner_guid', 'owner', 'time_created'
        ];
    protected $type = 'object';
    protected $subtype = 'message';
    protected $friendly_ts;
    public $message;
    protected $messages = [];
    protected $encrypted = true;
    protected $owner_guid;
    protected $owner;
    protected $time_created;

    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        $this->encrypted = false;
        return $this;
    }

    public function getMessage($user_guid = null)
    {
        if ($this->encrypted && $user_guid) {
            return $this->messages[$user_guid];
        }
        return $this->message;
    }

    public function setMessages($messages = [], $encrypted = true)
    {
        $this->messages = $messages;
        $this->encrypted = $encrypted;
        return $this;
    }

    public function encrypt()
    {
        //Di::_()->get('Messenger\Encryption')
                (new Messenger\Core\Encryption\OpenSSL())
                    ->setConversation($this->conversation)
                    ->setMessage($this)
                    ->encrypt();
        $this->encrypted = true;
        return $this;
    }

    public function decrypt($user, $password)
    {
        (new Messenger\Core\Encryption\OpenSSL())
                    ->setMessage($this)
                    ->setUser($user)
                    ->setUnlockPassword($password)
                    ->decrypt();
        $this->encrypted = false;
        return $this;
    }

        /**
         * Set the owner
         * @param Entity $owner
         * @return $this
         */
        public function setOwner($owner = null)
        {
            if (!($owner instanceof User)) {
                $owner = new User($owner);
            }

            $this->owner = $owner;
            return $this;
        }

        /**
         * Get the owner
         * @return User
         */
        public function getOwner()
        {
            return $this->owner ?: Session::getLoggedInUser();
        }

    public function save()
    {
        if (!$this->encrypted) {
            throw new \Exception('You can not save unencrypted messages');
        }
        $this->getGuid();
        $this->rowKey = "object:gathering:conversation:{$this->conversation->getGuid()}";
        $this->saveToDb([
                    'guid' => $this->getGuid(),
                    'messages' => $this->messages,
                    'time_created' => $this->time_created ?: time(),
                    'owner' => $this->getOwner()->export()
                    //'friendly_ts' => $this->friendly_ts
                ]);
                //var_dump($this->rowKey); exit;
    }

    public function deleteAll()
    {
        $this->getGuid();
        $rowKey = "object:gathering:conversation:{$this->conversation->getGuid()}";

            // TODO: Is there a way to empty the row without completely removing it?
            $this->db->removeRow($rowKey);
    }

    public function export(array $keys = [])
    {
        $export = parent::export($keys);
        $export = array_merge($export, Events\Dispatcher::trigger('export:extender', 'all', [ 'entity' => $this ], []));
        $export['ownerObj'] = $export['owner'];
        $export = \Minds\Helpers\Export::sanitize($export);
        return $export;
    }
}
