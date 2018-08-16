<?php

/**
 * Minds Offchain Transactions
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Data\Locks;
use Minds\Core\Data\Locks\LockFailedException;
use Minds\Core\Di\Di;
use Minds\Core\GuidBuilder;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Transactions
{
    /** @var Repository $repository */
    protected $repository;

    /** @var Balance $balance */
    protected $balance;

    /** @var Locks\Redis */
    protected $locks;

    /** @var GuidBuilder */
    protected $guid;

    /** @var User $user */
    protected $user;

    /** @var string $type */
    protected $type;

    /** @var double $amount */
    protected $amount;

    /** @var string $tx */
    protected $tx;

    /** @var array|null $data */
    protected $data;

    public function __construct($repository = null, $balance = null, $locks = null, $guid = null)
    {
        $this->repository = $repository ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->balance = $balance ?: Di::_()->get('Blockchain\Wallets\OffChain\Balance');
        $this->locks = $locks ?: Di::_()->get('Database\Locks');
        $this->guid = $guid ?: Di::_()->get('Guid');
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
        $this->amount = $amount;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function create()
    {
        $this->locks->setKey("balance:{$this->user->guid}");
        if ($this->locks->isLocked()) {
            throw new LockFailedException();
        }

        //create a lock of the balance
        try {
            $this->locks
                ->setTTL(120)
                ->lock();
        } catch (\Exception $e) {

        }

        $balance = BigNumber::_($this->balance->setUser($this->user)->get());

        if ($balance->add($this->amount)->lt(0)) {
            throw new \Exception('Not enough funds');
        }

        $transaction = new Transaction();

        $guid = $this->guid->build();

        $transaction
            ->setUserGuid($this->user->guid)
            ->setWalletAddress('offchain')
            ->setTimestamp(time())
            ->setTx('oc:' . $guid)
            ->setAmount($this->amount)
            ->setContract('offchain:' . $this->type)
            ->setCompleted(true);

        if ($this->data) {
            $transaction->setData($this->data);
        }

        $added = $this->repository->add($transaction);

        if (!$added) {
            throw new \Exception("Could not add transaction");
        }

        //release the lock?
        $this->locks->unlock();

        return $transaction;
    }

    public function toWei($value)
    {
        return (string) BigNumber::toPlain($value, 18);
    }

}
