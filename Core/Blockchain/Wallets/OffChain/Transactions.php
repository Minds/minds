<?php

/**
 * Minds Offchain Transactions
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Entities\User;
use Minds\Core\Di\Di;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Guid;

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
        $this->repository = $repository ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->balance = $balance ?: Di::_()->get('Blockchain\Wallets\OffChain\Balance');
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

    public function create()
    {
        $balance = (double) $this->balance->setUser($this->user)->get();
        
        if ($balance + $this->amount < (double) 0) {
            throw new \Exception('Not enough funds');
        }

        $transaction = new Transaction();

        $transaction
            ->setUserGuid($this->user->guid)
            ->setWalletAddress('offchain')
            ->setTimestamp(time())
            ->setTx('oc:' . Guid::build())
            ->setAmount($this->amount)
            ->setContract('offchain:' . $this->type)
            ->setCompleted(true);

        $added = $this->repository->add($transaction);

        if (!$added) {
            throw new \Exception("Could not add transaction");
        }

        return $transaction;
    }

    public function toWei($value)
    {
        return (double) $value * (10 ** 18);
    }

}
