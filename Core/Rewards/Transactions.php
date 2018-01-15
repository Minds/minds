<?php

/**
 * Minds Rewards Transactions
 *
 * @author emi
 */

namespace Minds\Core\Rewards;

use Minds\Entities\User;

class Transactions
{
    /** @var Repository $repository */
    protected $repository;

    /** @var Balance $balance */
    protected $balance;

    /** @var User $user */
    protected $user;

    public function __construct($repository = null, $balance = null)
    {
        $this->repository = $repository ?: new Repository();
        $this->balance = $balance ?: new Balance();
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function create($amount, $type)
    {
        $balance = $this->balance->setUser($this->user)->get();

        if ($balance + $amount < 0) {
            throw new \Exception('Not enough funds');
        }

        $reward = new Reward();

        $reward
            ->setUser($this->user)
            ->setAmount($amount)
            ->setType($type);

        $added = $this->repository->add($reward);

        if (!$added) {
            throw new \Exception("Could not add transaction");
        }

        return "reward-tx:{$this->user->guid}:{$type}:{$reward->getTimestamp()}";
    }

    public function toWei($value)
    {
        return (double) $value * (10 ** 18);
    }
}
