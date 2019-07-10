<?php

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Message;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Manager;
use Minds\Entities\User;
use Minds\Traits\MagicAttributes;
use Minds\Core\Email\CampaignLogs\CampaignLog;

abstract class EmailCampaign
{
    use MagicAttributes;
    protected $campaign;
    protected $topic;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Message
     */
    abstract public function send();

    /**
     * Determines whether or not we can send an email to a user.
     * Requires setting the user object and setting the manager.
     */
    public function canSend()
    {
        if (
            !$this->user
            || !$this->user instanceof \Minds\Entities\User
            || $this->user->enabled != 'yes'
        ) {
            return false;
        }

        $emailSubscription = (new EmailSubscription())
            ->setUserGuid($this->user->guid)
            ->setCampaign($this->campaign)
            ->setTopic($this->topic)
            ->setValue(true);

        if (!$this->manager || !$this->manager instanceof \Minds\Core\Email\Manager || !$this->manager->isSubscribed($emailSubscription)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the short name of the class as the template name.
     */
    public function getEmailCampaignId()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Saves when the user received the email campaign to the db.
     *
     * @var int defaults to the current time
     */
    public function saveCampaignLog(int $time = null)
    {
        $time = $time ?: time();
        if (!$this->manager || !$this->user) {
            return false;
        }
        $campaignLog = (new CampaignLog())
            ->setReceiverGuid($this->user->guid)
            ->setTimeSent($time)
            ->setEmailCampaignId($this->getEmailCampaignId());

        $this->manager->saveCampaignLog($campaignLog);
    }
}
