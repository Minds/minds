<?php
/**
 * Syncs a users contributions
 */
namespace Minds\Core\Rewards\Contributions;

use Minds\Core\Analytics;

class Manager
{

    protected $analytics;
    protected $repository;
    protected $user;
    protected $from;
    protected $to;
    protected $dryRun = false;
    protected $site_contribtion_score_cache = [];

    public function __construct($analytics = null, $repository = null, $sums = null)
    {
        $this->analytics = $analytics ?: new Analytics\Manager();
        $this->repository = $repository ?: new Repository;
        $this->sums = $sums ?: new Sums;
        $this->from = strtotime('-7 days') * 1000;
        $this->to = time() * 1000;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Sets if to dry run or not. A dry run will return the data but will save
     * to the database
     * @param bool $dryRun
     * @return $this
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function sync()
    {
        $this->analytics
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setInterval('day');

        if ($this->user) { 
            $this->analytics->setUser($this->user);
        }

        $rewards = [];
        foreach($this->analytics->getCounts() as $ts => $data) {
            foreach($data as $metric => $count) {
                $multiplier = ContributionValues::$multipliers[$metric];
                $reward = new Contribution();
                $reward->setMetric($metric)
                    ->setTimestamp($ts)
                    ->setAmount($count * $multiplier);

                if ($this->user) {
                    $reward->setUser($this->user);
                }
                $rewards[] = $reward;
            }
        }


        if ($this->dryRun) {
            return $rewards;
        }

        $this->repository->add($rewards);       

        return $rewards; 
    }

    /**
     * Gather the entire site contribution score
     */
    protected function getSiteContribtionScore()
    {
        if (isset($this->site_contribtion_score_cache[$this->from])) {
            return $this->site_contribtion_score_cache[$this->from];
        }
        return $this->site_contribtion_score_cache[$this->from] = $this->sums
            ->setTimestamp($this->from)
            ->setUser(null)
            ->getAmount();
    }

    /**
     * Gather the contribution score for the user
     * @return int
     */
    protected function getUserContributionScore()
    {
        return $this->sums
            ->setTimestamp($this->from)
            ->setUser($this->user)
            ->getAmount();
    }

    /**
     * Return the number of tokens to be rewarded
     * @return int
     */
    public function getRewardsAmount()
    {
        $share = $this->getUserContributionScore() / $this->getSiteContribtionScore();
        $pool = ((100000000 * (10 ** 18)) / 4) / 365;

        return round($share * $pool, 0);
    }

}
