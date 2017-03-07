<?php
namespace Minds\Core\Monetization;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;

class Merchants
{
    public function __construct()
    {
    }

    public function setUser($user)
    {
        if (!is_object($user)) {
            $user = new Entities\User($user);
        }

        if (!$user || !$user->guid) {
            throw new \Exception('Invalid user');
        }

        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function refreshUser()
    {
        if (!$this->user) {
            return;
        }

        $this->setUser(new Entities\User($this->user->guid, false));
    }

    public function getId()
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        if ($this->user->ban_monetization === 'yes') {
            return false;
        }

        $merchant = $this->user->getMerchant();

        if (!$merchant || $merchant['service'] !== 'stripe') {
            return false;
        }

        return $merchant['id'];
    }

    public function ban()
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        $this->user->ban_monetization = 'yes';
        $this->user->save();

        $payouts = Di::_()->get('Monetization\Payouts');
        $payouts->setUser($this->user);

        $lastPayout = $payouts->getLastPayout();

        if ($lastPayout && $lastPayout['status'] === 'inprogress') {
            $payouts->cancel($lastPayout['guid']);
        }

        return true;
    }

    public function unban()
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        $this->user->ban_monetization = 'no';
        $this->user->save();

        return true;
    }

    public function isBanned()
    {
        return $this->user->ban_monetization === 'yes';
    }
}
