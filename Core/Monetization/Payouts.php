<?php
namespace Minds\Core\Monetization;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;
use Minds\Core\Payments\Stripe\Stripe;
use Minds\Core\Payments\Transfers\Transfer;

class Payouts
{
    protected $config;
    protected $stripe;

    // Instance-only cache
    protected $lastPayoutCache = null;

    public function __construct($config = null, $stripe = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
    }

    public function setUser($user)
    {
        if (is_object($user)) {
            $user = $user->guid;
        }

        $this->user = $user;

        $this->lastPayoutCache = null;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLastPayout($disableCache = false)
    {
        if (!$disableCache && $this->lastPayoutCache !== null) {
            return $this->lastPayoutCache;
        }

        $manager = Di::_()->get('Monetization\Manager');
        $payouts = $manager->get([
            'type' => 'credit',
            'user_guid' => $this->user,
            'limit' => 1,
            'order' => 'DESC'
        ]);

        if (!$payouts) {
            $this->lastPayoutCache = false;
            return false;
        }

        $this->lastPayoutCache = $payouts[0];
        return $payouts[0];
    }

    public function getPayoutStatus()
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        $lastPayout = $this->getLastPayout();

        if ($lastPayout && $lastPayout['status'] === 'inprogress') {
            return 'inprogress';
        }

        return 'available';
    }

    public function canRequestPayout()
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        $lastPayout = $this->getLastPayout();
        $dateRange = $this->getPayoutDateRange(true);

        if (!$dateRange) {
            return false;
        }

        $ads = Di::_()->get('Monetization\Ads');
        $ads->setUser($this->user);

        return (!$lastPayout || $lastPayout['status'] != 'inprogress') &&
            $this->calcUserAmount($ads->getTotalRevenue($dateRange['start'], $dateRange['end'])) >= $this->config->get('payouts')['minimumAmount'];
    }

    public function requestPayout()
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        $lastPayout = $this->getLastPayout(true);

        if ($lastPayout && $lastPayout['status'] === 'inprogress') {
            throw new \Exception('Payout still in process');
        }

        $ads = Di::_()->get('Monetization\Ads');
        $ads->setUser($this->user);

        $dateRange = $this->getPayoutDateRange(true);

        if (!$dateRange) {
            throw new \Exception('Wrong date range');
        }

        $amount = $this->calcUserAmount($ads->getTotalRevenue($dateRange['start'], $dateRange['end']));
        $amount = (int) floor($amount * 100); // Amount is stored in cents
        if ($amount < $this->config->get('payouts')['minimumAmount']) {
            throw new \Exception('Payout amount too small');
        }

        $guid = Core\Guid::build();
        $manager = Di::_()->get('Monetization\Manager');
        $manager->insert([
            'guid' => (string) $guid,
            'user_guid' => (string) $this->user,
            'type' => 'credit',
            'status' => 'inprogress',
            'amount' => $amount,
            'ts' => time(),
            'start' => $dateRange['start']->getTimestamp(),
            'end' => $dateRange['end']->getTimestamp(),
        ]);

        $this->lastPayoutCache = null;

        Core\Events\Dispatcher::trigger('notification', 'payout', [
            'to'=> [ $this->user ],
            'from' => 100000000000000519,
            'notification_view' => 'payout_queued',
            'params' => [ 'guid' => $guid, 'amount' => (float) ($amount / 100) ]
        ]);

        return $guid;
    }

    public function getPayoutDateRange($fresh = false)
    {
        $lastPayout = $this->getLastPayout();

        if (!$fresh && $lastPayout && $lastPayout['status'] === 'inprogress') {
            $start = new \DateTime("@{$lastPayout['start']}");
            $end = new \DateTime("@{$lastPayout['end']}");
        } elseif ($lastPayout) {
            $start = new \DateTime("@{$lastPayout['end']}");
            $start->modify('+1 day');
            $end = new \DateTime($this->getRetentionDateString());
        } else {
            $start = new \DateTime($this->config->get('payouts')['initialDate']);
            $end = new \DateTime($this->getRetentionDateString());
        }

        if ($end < $start) {
            return false;
        }

        return [
            'start' => $start,
            'end' => $end
        ];
    }
    // Payments

    public function payout($guid)
    {
        $manager = Di::_()->get('Monetization\Manager');
        $payout = $manager->resolve($guid);

        if (!$payout || $payout['status'] !== 'inprogress') {
            throw new \Exception('Invalid payout');
        }

        $merchants = Di::_()->get('Monetization\Merchants');
        $merchants->setUser(Core\Sandbox::user($payout['user_guid'], 'merchant'));
        $merchantId = $merchants->getId();

        if (!$merchantId) {
            throw new \Exception('Invalid merchant account');
        }

        $transfer = new Transfer();
        $transfer
            ->setAmount((int) $payout['amount']) // Already in cents!
            ->setDestination($merchantId)
            ->setSource([
                'concept' => 'payout',
                'guid' => (string) $guid,
            ]);

        $transfer = $this->stripe->transfer($transfer);

        if (!$transfer) {
            throw new \Exception('Internal Stripe error');
        }

        $done = $manager->update($guid, [
            'status' => 'paid',
            'service_id' => $transfer->getId()
        ], $payout);

        if ($done) {
            $this->lastPayoutCache = null;

            Core\Events\Dispatcher::trigger('notification', 'payout', [
                'to'=> [ $payout['user_guid'] ],
                'from' => 100000000000000519,
                'notification_view' => 'payout_accepted',
                'params' => [ 'guid' => $guid, 'amount' => (float) ($payout['amount'] / 100) ]
            ]);
        }

        return (bool) $done;
    }

    public function cancel($guid)
    {
        $manager = Di::_()->get('Monetization\Manager');
        $payout = $manager->resolve($guid);

        if (!$payout || $payout['status'] !== 'inprogress') {
            throw new \Exception('Invalid payout');
        }

        $done = $manager->update($guid, [
            'status' => 'cancelled'
        ], $payout);

        if ($done) {
            $this->lastPayoutCache = null;

            Core\Events\Dispatcher::trigger('notification', 'payout', [
                'to' => [ $payout['user_guid'] ],
                'from' => 100000000000000519,
                'notification_view' => 'payout_declined',
                'params' => [ 'guid' => $guid, 'amount' => (float) ($payout['amount'] / 100) ]
            ]);
        }

        return (bool) $done;
    }

    public function calcUserAmount($amount)
    {
        return (float) ($amount * $this->config->get('payouts')['userPercentage']);
    }

    public function getRetentionDateString()
    {
        return $this->config->get('payouts')['retentionDays'] . ' days ago';
    }

    // Helpers methods
    // @note: No spec tests for this as they're just for user-facing data

    public function getOverview()
    {
        $ads = Di::_()->get('Monetization\Ads');
        $ads->setUser($this->user);

        $dateRange = $this->getPayoutDateRange();

        return [
            'status' => $this->getPayoutStatus(),
            'available' => $this->canRequestPayout(),
            'amount' => $dateRange ? $this->calcUserAmount($ads->getTotalRevenue($dateRange['start'], $dateRange['end'])) : 0,
            'dates' => [
                'start' => $dateRange ? $dateRange['start']->getTimestamp() : false,
                'end' => $dateRange ? $dateRange['end']->getTimestamp() : false,
            ],
        ];
    }
}
