<?php
/**
 * Syncs a users contributions to rewards values
 */
namespace Minds\Core\Rewards;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Di\Di;
use Minds\Entities\User;
use Minds\Core\Guid;
use Minds\Core\Util\BigNumber;

class Manager
{

    /** @var Contributions\Manager $contributions */
    protected $contributions;

    /** @var Transactions $transactions */
    protected $transactions;

    /** @var Repository $txTransactions */
    protected $txRepository;

    /** @var Ethereum $eth */
    protected $eth;

    /** @var Config $config */
    protected $config;

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
        $txRepository = null,
        $eth = null,
        $config = null
    )
    {
        $this->contributions = $contributions ?: new Contributions\Manager;
        $this->transactions = $transactions ?: Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
        $this->txRepository = $txRepository ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->config = $config ?: Di::_()->get('Config');
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
            'wallet_address' => 'offchain', //removed because of allow filtering issues.
            'timestamp' => [
                'gte' => $this->from,
                'lte' => $this->to,
                'eq' => null,
            ],
            'contract' => 'offchain:reward',
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
 
        $transaction = new Transaction(); 
        $transaction
            ->setUserGuid($this->user->guid)
            ->setWalletAddress('offchain')
            ->setTimestamp(strtotime("+24 hours - 1 second", $this->from / 1000))
            ->setTx('oc:' . Guid::build())
            ->setAmount($amount)
            ->setContract('offchain:reward')
            ->setCompleted(true);

        if ($this->dryRun) {
            return $transaction;
        }

        $this->txRepository->add($transaction);

        try {
            $this->bonus();
        } catch (\Exception $e) { }

        //$this->txRepository->delete($this->user->guid, strtotime("+24 hours - 1 second", $this->from / 1000), 'offchain');
        return $transaction;
    }

    public function bonus()
    {
        $this->contributions
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setUser($this->user);

        if (!$this->user || !$this->user->eth_wallet) { 
            return;
        }

        $amount = $this->contributions->getRewardsAmount();

        if ($amount <= 0) {
            return;
        } 

        $res = $this->eth->sendRawTransaction($this->config->get('blockchain')['contracts']['bonus']['wallet_pkey'], [
            'from' => $this->config->get('blockchain')['contracts']['bonus']['wallet_address'],
            'to' => $this->config->get('blockchain')['token_address'],
            'gasLimit' => BigNumber::_(4612388)->toHex(true),
            'gasPrice' => BigNumber::_(10000000000)->toHex(true),
            //'nonce' => (int) microtime(true),
            'data' => $this->eth->encodeContractMethod('transfer(address,uint256)', [
                $this->user->eth_wallet,
                BigNumber::_($amount)->mul(0.25)->toHex(true),
            ])
        ]);
        
        $transaction = new Transaction(); 
        $transaction
            ->setUserGuid($this->user->guid)
            ->setWalletAddress($this->user->eth_wallet)
            ->setTimestamp(time())
            ->setTx($res)
            ->setAmount((string) BigNumber::_($amount)->mul(0.25))
            ->setContract('bonus')
            ->setCompleted(true);
    
        $this->txRepository->add($transaction);
        return $transaction;
    }

}

