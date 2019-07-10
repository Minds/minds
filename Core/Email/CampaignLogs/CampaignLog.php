<?php

namespace Minds\Core\Email\CampaignLogs;

use Minds\Traits\MagicAttributes;

/**
 * @method int getReceiverGuid()
 * @method CampaignLog setReceiverGuid(int $receiverGuid)
 * @method int getTimeSent()
 * @method CampaignLog setTimeSet(int $timeSent)
 * @method string getEmailCampaignId()
 * @method CampaignLog setEmailCampaignId(string $emailCampaignId)
 */

class CampaignLog
{
    use MagicAttributes;

    /** @var int $receiverGuid the user guid who received the email */
    protected $receiverGuid;

    /** @var int $timeStamp the timestamp when the email was sent */
    protected $timeSent;

    /** @var string $emailCampaignId the class name of the email campaign */
    protected $emailCampaignID;
}
