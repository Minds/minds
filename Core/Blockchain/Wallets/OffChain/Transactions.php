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
        } catch (\Exception $e) { }

        try {
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

            // Release the lock
            $this->locks->unlock();

            return $transaction;
        } catch (\Exception $e) {
            // Release the lock
            $this->locks->unlock();

            // Rethrow
            throw $e;
        }
    }

    /**
     * @param User $sender
     * @return bool
     * @throws LockFailedException
     * @throws Locks\KeyNotSetupException
     */
    public function transferFrom(User $sender)
    {
        $receiver = $this->user;

        if (!$receiver || !$receiver->guid) {
            throw new \Exception('Invalid receiver');
        }

        if (!$sender || !$sender->guid) {
            throw new \Exception('Invalid sender');
        }

        $receiverLockKey = "balance:{$receiver->guid}";
        $senderLockKey = "balance:{$sender->guid}";

        if (
            $this->locks->setKey($receiverLockKey)->isLocked() ||
            $this->locks->setKey($senderLockKey)->isLocked()
        ) {
            throw new LockFailedException();
        }

        // Create a lock of the balance

        $balanceLockTtl = 120;

        try {
            $this->locks
                ->setKey($receiverLockKey)
                ->setTTL($balanceLockTtl)
                ->lock();

            $this->locks
                ->setKey($senderLockKey)
                ->setTTL($balanceLockTtl)
                ->lock();
        } catch (\Exception $e) { }

        try {
            // Amounts

            $receiverAmount = (string) $this->amount;
            $senderAmount = (string) BigNumber::_($this->amount)->neg();

            // Check that number is not a negative

            if (BigNumber::_($receiverAmount)->lte(0)) {
                throw new \Exception('Amount should be greater than 0');
            }

            // Check sender funds

            $balance = BigNumber::_($this->balance->setUser($sender)->get());

            if ($balance->add($senderAmount)->lt(0)) {
                throw new \Exception('Not enough sender funds');
            }

            // Receiver Transaction

            $receiverTxGuid = $this->guid->build();

            $receiverTx = new Transaction();
            $receiverTx
                ->setUserGuid($receiver->guid)
                ->setWalletAddress('offchain')
                ->setTimestamp(time())
                ->setTx('oc:' . $receiverTxGuid)
                ->setAmount($receiverAmount)
                ->setContract('offchain:' . $this->type)
                ->setCompleted(true);

            if ($this->data) {
                $receiverTx->setData($this->data);
            }

            // Sender Transaction

            $senderTxGuid = $this->guid->build();

            $senderTx = new Transaction();
            $senderTx
                ->setUserGuid($sender->guid)
                ->setWalletAddress('offchain')
                ->setTimestamp(time())
                ->setTx('oc:' . $senderTxGuid)
                ->setAmount($senderAmount)
                ->setContract('offchain:' . $this->type)
                ->setCompleted(true);

            if ($this->data) {
                $senderTx->setData($this->data);
            }

            // Create transaction, first debit from sender, then credit receiver

            $this->repository->add([$senderTx, $receiverTx]);

            // Release the locks

            $this->locks
                ->setKey($receiverLockKey)
                ->unlock();

            $this->locks
                ->setKey($senderLockKey)
                ->unlock();

            //

            return true;
        } catch (\Exception $e) {
            // Release the locks

            $this->locks
                ->setKey($receiverLockKey)
                ->unlock();

            $this->locks
                ->setKey($senderLockKey)
                ->unlock();

            // Rethrow

            throw $e;
        }
    }

    public function toWei($value)
    {
        return (string) BigNumber::toPlain($value, 18);
    }

}
