<?php
/**
 * Gatherings message entity model
 *
 * A message contains information about a message in a thread.
 */

namespace minds\plugin\gatherings\entities;

use Minds\Entities\Object;
use minds\plugin\gatherings\helpers;

class message extends object
{
    private $conversation;
    public $subtype = 'message';
    private $passphrase = null;

    public function __construct($guid = null)
    {
        $this->initializeAttributes();

        if (is_object($guid) && $guid instanceof conversation) {
            //loading from a conversation object
            $this->setConversation($guid);
        } else {
            parent::__construct($guid);
        }

        $this->subtype = 'message';
    }

    protected function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'access_id' => ACCESS_PRIVATE,
            'owner_guid'=> \elgg_get_logged_in_user_guid(),
            'subtype' => 'message'
        ));
    }

    /**
     * Override the default indexes
     */
    protected function getIndexKeys($ia = false)
    {
        $indexes = array();

        foreach ($this->conversation->getIndexKeys() as $ik) {
            $indexes[] = "object:gathering:conversation:$ik";
        }

        return $indexes;
    }

    private function setConversation($conversation)
    {
        $this->conversation = $conversation;
        $this->conversation->update();
    }

    /**
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function save($timebased = true)
    {
        if (!$this->conversation) {
            throw new \Exception('Can not save a message without a conversation');
        }

        $this->conversation->update();
        return    parent::save($timebased);
    }

    public function delete()
    {
        $db = new \Minds\Core\Data\Call('entities');
        return $db->removeRow($this->guid);
    }

    /**
     * Encrypt the message
     *
     * We store multiple versions of the message, as per the recipients public key, so that they can decrypt the message with their private key.
     */
    public function encryptMessage($message)
    {
        foreach ($this->conversation->participants as $user_guid) {
            $key = "message:$user_guid";
            $public_key = \elgg_get_plugin_user_setting('publickey', $user_guid, 'gatherings');

            if ($public_key) {
                $encrypted = helpers\openssl::encrypt($message, $public_key);
                $this->$key = base64_encode($encrypted);
            }
        }
    }

    public function decryptMessage($participant_guid = null, $passphrase = null)
    {
        if (!$participant_guid) {
            $participant_guid = elgg_get_logged_in_user_guid();
        }

        $key = "message:$participant_guid";
        if (isset($_SESSION['tmp_privatekey'])) {
            $private_key = $_SESSION['tmp_privatekey'];
        } else {
            $private_key =  \elgg_get_plugin_user_setting('privatekey', $participant_guid, 'gatherings');
        }

        $option = \elgg_get_plugin_user_setting('option', $participant_guid, 'gatherings');
        if ($private_key && (int) $option == 1) {
            $this->message = helpers\openssl::decrypt(base64_decode($this->$key), $private_key, $passphrase);
            return $this->message;
        }

        return $this->$key;
    }


    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(),
            array(
                "friendly_ts",
                "message",
                "message:".elgg_get_logged_in_user_guid()
            ));
    }
}
