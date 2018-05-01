<?php

/**
 * Minds Contributions Overview
 *
 * @author emi
 */

namespace Minds\Core\Rewards\Contributions;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Overview
{
    /** @var Sums */
    protected $sums;

    /** @var Repository */
    protected $repository;

    /** @var Manager */
    protected $manager;

    /** @var abstractCacher */
    protected $cache;

    /** @var User */
    protected $user;

    /** @var BigNumber */
    protected $currentReward = '0';

    /** @var int */
    protected $yourContribution = 0;

    /** @var int */
    protected $totalNetworkContribution = 0;

    /** @var float */
    protected $yourShare = 0;

    public function __construct($sums = null, $repository = null, $manager = null, $cache = null)
    {
        $this->sums = $sums ?: new Sums();
        $this->repository = $repository ?: new Repository();
        $this->manager = $manager ?: new Manager();
        $this->cache = $cache ?: Di::_()->get('Cache');
    }

    /**
     * @param User $user
     * @return Overview
     */
    public function setUser($user)
    {
        $this->user = $user;

        $this->currentReward = BigNumber::_(0);
        $this->yourContribution = 0;
        $this->totalNetworkContribution = 0;
        $this->yourShare = 0;

        return $this;
    }

    /**
     * Calculates user stats
     * @return $this
     */
    public function calculate($fromCache = true)
    {
        if (!$this->user) {
            return $this;
        }

        $cacheKey = "rewards:contributions:overview:{$this->user->guid}";

        if ($fromCache && ($cached = $this->cache->get($cacheKey))) {
            $cached = unserialize($cached);

            $this->totalNetworkContribution = $cached['totalNetworkContribution'];
            $this->yourContribution = $cached['yourContribution'];
            $this->currentReward = $cached['currentReward'];
            $this->yourShare = $cached['yourShare'];

            return $this;
        }

        $timestamp = $this->getLastTimestamp();

        if (!$timestamp) {
            return $this;
        }

        $this->sums->setTimestamp($timestamp);

        $this->manager->setUser($this->user);
        $this->manager->setFrom($timestamp);
        $this->totalNetworkContribution = $this->manager->getSiteContribtionScore();
        $this->yourContribution = $this->manager->getUserContributionScore();
        $this->currentReward = $this->manager->getRewardsAmount();

        $this->yourShare = $this->yourContribution / ($this->totalNetworkContribution ?: 1);

        $this->cache->set($cacheKey, serialize([
            'totalNetworkContribution' => $this->totalNetworkContribution,
            'yourContribution' => $this->yourContribution,
            'currentReward' => $this->currentReward,
            'yourShare' => $this->yourShare,
        ]), 15 * 60);

        return $this;
    }

    /**
     * Gets time for next payout in seconds
     * @return int
     */
    public function getNextPayout()
    {
        $hour = 2;
        $day = gmdate('G') >= $hour ? 'tomorrow' : 'today';
        $timestamp = strtotime($day);
        $timestamp = strtotime("+2 hours", $timestamp);

        return $timestamp - time();
    }

    /**
     * @return BigNumber
     */
    public function getCurrentReward()
    {
        return $this->currentReward;
    }

    /**
     * @return int
     */
    public function getYourContribution()
    {
        return $this->yourContribution;
    }

    /**
     * @return int
     */
    public function getTotalNetworkContribution()
    {
        return $this->totalNetworkContribution;
    }

    /**
     * @return float
     */
    public function getYourShare()
    {
        return $this->yourShare;
    }

    /**
     * Returns the last timestamp stored for a user's contribution
     * @return bool|float
     */
    protected function getLastTimestamp()
    {
        return strtotime('midnight -24 hours', $this->getNextPayout() + time()) * 1000;
    }
}
