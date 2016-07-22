<?php
/**
 * Call missed messages
 */

namespace minds\plugin\gatherings\entities;

use Minds\Entities\Object;
use minds\plugin\gatherings\helpers;

class CallMissed extends message
{
    private $conversation;
    private $message;
    public $subtype = 'call_missed';
    private $passphrase = null;
    public $client_encrypted = false;

    public function __construct($guid = null, $passphrase = null)
    {
        parent::__construct($guid, $passphrase);

        $this->subtype = 'call_missed';
    }

    protected function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'access_id' => ACCESS_PRIVATE,
            'owner_guid'=> \elgg_get_logged_in_user_guid(),
            'subtype' => 'call_missed'
        ));
    }

    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(),
            array(
                "friendly_ts"
            ));
    }
}
