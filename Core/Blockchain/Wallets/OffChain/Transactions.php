<?php

/**
 * Minds Offchain Transactions
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Data\Cassandra\Locks\Locks;
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

    /** @var Locks locks */
    protected $locks;

    /** @var User $user */
    protected $user;

    /** @var string $type */
    protected $type;

    /** @var double $amount */
    protected $amount;

    /** @var string $tx */
    protected $tx;

    public function __construct($repository = null, $balance = null, $locks = null)
    {
        $this->repository = $repository ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->balance = $balance ?: Di::_()->get('Blockchain\Wallets\OffChain\Balance');
        $this->locks = $locks ?: Di::_()->get('Database\Cassandra\Locks');
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
        $this->locks->setKey("balance:{$this->user->guid}");
        if ($this->locks->isLocked()) {
            throw new \Exception('Offchain Wallet is locked');
        }

        //create a lock of the balance
        $this->locks
            ->setTTL(120)
            ->lock();

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

        //release the lock?
        //$this->locks->unlock();

        return $transaction;
    }

    public function toWei($value)
    {
        return (double) $value * (10 ** 18);
    }

}
