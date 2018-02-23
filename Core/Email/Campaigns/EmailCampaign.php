<?php

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Message;
use Minds\Entities\User;

abstract class EmailCampaign
{
    protected $campaign;
    protected $topic;

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
}