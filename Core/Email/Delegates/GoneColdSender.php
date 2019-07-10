<?php

namespace Minds\Core\Email\Delegates;

use Minds\Core\Suggestions\Manager;
use Minds\Entities\User;
use Minds\Core\Di\Di;
use Minds\Interfaces\SenderInterface;
use Minds\Core\Email\Campaigns\UserRetention\GoneCold;

class GoneColdSender implements SenderInterface
{
    /** @var Manager */
    private $manager;
    /** @var GoneCold */
    private $campaign;

    public function __construct(Manager $manager = null, GoneCold $campaign = null)
    {
        $this->manager = $manager ?: Di::_()->get('Suggestions\Manager');
        $this->campaign = $campaign ?: new GoneCold();

    }

    public function send(User $user)
    {
        $this->manager->setUser($user);
        $suggestions = $this->manager->getList();
        $this->campaign->setUser($user);
        $this->campaign->setSuggestions($suggestions);
        $this->campaign->send();
    }
}
