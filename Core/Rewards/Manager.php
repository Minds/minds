<?php
/**
 * Syncs a users contributions to rewards values
 */
namespace Minds\Core\Rewards;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class Manager
{

    /** @var Contributions\Manager $contributions */
    protected $contributions;

    /** @var Transactions $transactions */
    protected $transactions;

    /** @var Repository $txTransactions */
    protected $txRepository;

    /** @var User $user */
    protected $user;

    /** @var int $from */
    protected $from;

    /** @var int $to */
    protected $to;

    /** @var bool $dryRun */
    protected $dryRun = false;

    public function __construct(
        $contributions = null,
        $transactions = null,
        $txRepository = null
    )
    {
        $this->contributions = $contributions ?: new Contributions\Manager;
        $this->transactions = $transactions ?: Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
        $this->txRepository = $txRepository ?: Di::_()->get('Blockchain\Transactions\Repository');
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
        $transactions = $this->txRepository->getList([
            'user_guid' => $this->user->guid,
            'wallet_address' => 'offchain',
            'timestamp' => [
                'gte' => $this->from,
                'lte' => $this->to,
            ],
            'contract' => 'oc:reward',
        ]);

        if ($transactions['transactions']) {
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
 
        $this->transactions
            ->setUser($this->user)
            ->setType('reward')
            ->setAmount($amount);

        if ($this->dryRun) {
            return $this->transactions;
        }

        Dispatcher::trigger('notification', 'contributions', [
            'to' => [$this->user->guid],
            'from' => 100000000000000519,
            'notification_view' => 'contributions',
            'params' => ['amount' => $amount],
            'message' => ''
        ]);
    
        return $this->transactions->create();
    }

}
