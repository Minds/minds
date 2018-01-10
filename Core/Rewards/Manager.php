<?php
/**
 * Syncs a users contributions to rewards values
 */
namespace Minds\Core\Rewards;

class Manager
{

    protected $contributions;
    protected $repository;
    protected $user;
    protected $from;
    protected $to;
    protected $dryRun = false;

    public function __construct($contributions = null, $repository = null)
    {
        $this->contributions = $contributions ?: new Contributions\Manager;
        $this->repository = $repository ?: new Repository;
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

        //First double check that we have not already credited them any
        //rewards for this timeperiod
        $rewards = $this->repository->getList([
            'timestamp' => $this->from,
            'type' => 'contribution',
            'user_guid' => $this->user->guid
        ]);

        if ($rewards) {
            throw new \Exception("Already issued rewards to this user");
        }

        $this->contributions
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setUser($this->user);

        if ($this->user) { 
            $this->contributions->setUser($this->user);
        }

        $amount = $this->contributions->getRewardsAmount();
 
        $reward = new Reward();
        $reward->setType('contribution')
            ->setTimestamp($this->from)
            ->setUser($this->user)
            ->setAmount($amount);


        if ($this->dryRun) {
            return $reward;
        }

        $this->repository->add($reward);        
    
        return $reward;
    }

}
