<?php

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Message;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Manager;
use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

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
}
