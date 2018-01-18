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

    /** @var string $type */
    protected $type;

    /** @var double $amount */
    protected $amount;

    /** @var string $tx */
    protected $tx;

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

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = (double) $amount;
        return $this;
    }

    public function setTx($tx)
    {
        $this->tx = $tx;
        return $this;
    }

    public function create()
    {
        $balance = (double) $this->balance->setUser($this->user)->get();
        
        if ($balance + $this->amount < (double) 0) {
            throw new \Exception('Not enough funds');
        }

        $reward = new Reward();

        $reward
            ->setUser($this->user)
            ->setAmount($this->amount)
            ->setType($this->type)
            ->setTx($this->tx);

        $added = $this->repository->add($reward);

        if (!$added) {
            throw new \Exception("Could not add transaction");
        }

        return "reward-tx:{$this->user->guid}:{$this->type}:{$reward->getTimestamp()}";
    }

    public function toWei($value)
    {
        return (double) $value * (10 ** 18);
    }
}
