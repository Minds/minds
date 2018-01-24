<?php
/**
 * Manages reward withdrawals to the blockchain
 */
namespace Minds\Core\Rewards\Withdraw;

use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Util;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Rewards\Transactions;
use Minds\Entities\User;

class Manager
{

    /** @var \Minds\Core\Blockchain\Transactions\Manager */
    protected $blockchainTx;

    /** @var Pending $pending */
    protected $pending;

    /** @var Transactions $transactions */
    protected $transactions;

    /** @var Config $config */
    protected $config;

    /** @var \Minds\Core\Rewards\Withdraw\Repository */
    protected $repo;

    public function __construct(
        $blockchainTx = null,        
        $transactions = null,
        $config = null,
        $eth = null,
        $withdrawRepository = null
    )
    {
        $this->blockchainTx = $blockchainTx ?: Di::_()->get('Blockchain\Transactions\Manager');        
        $this->transactions = $transactions ?: Di::_()->get('Rewards\Transactions');
        $this->config = $config ?: Di::_()->get('Config');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->repo = $withdrawRepository ?: Di::_()->get('Rewards\Withdraw\Repository');
    }

    /**
     * Create a request
     * @param Request $request
     * @return void
     */
    public function request($request)
    {
        $transaction = new Transaction();
        $transaction
            ->setTx($request->getTx())
            ->setContract('withdraw')
            ->setTimestamp($request->getTimestamp())
            ->setUserGuid($request->getUserGuid())
            ->setData([
                'amount' => $request->getAmount(),
                'gas' => $request->getGas(),
                'address' => $request->getAddress(),
            ]);

        $this->repo->add($request);
        $this->blockchainTx->add($transaction);
    }

    /**
     * Complete the requested transaction
     * @param Request $request
     * @param Transaction $transaction - the transaction we store
     * @return void
     */
    public function complete($request, $transaction)
    {
        
        if ($request->getUserGuid() != $transaction->getUserGuid()) {
            throw new \Exception('The user who requested this operation does not match the transaction');
        }

        if ($request->getAddress() != $transaction->getData()['address']) {
            throw new \Exception('The address does not match the transaction');
        }

        if ($request->getAmount() != $transaction->getData()['amount']) {
            throw new \Exception('The amount request does not match the transaction');
        }

        if ($request->getGas() != $transaction->getData()['gas']) {
            throw new \Exception('The gas requested does not match the transaction');
        }

        //debit the users balance
        $user = new User;
        $user->guid = (string) $request->getUserGuid();
        
        $this->transactions
            ->setUser($user)
            ->setType('withdrawal')
            ->setTx($request->getTx())
            ->setAmount(0 - $request->getAmount())
            ->create();

        $request->setCompleted(true);
        $this->repo->add($request);

        //now issue the transaction
        $res = $this->eth->sendRawTransaction($this->config->get('blockchain')['rewards_wallet_pkey'], [
            'from' => $this->config->get('blockchain')['rewards_wallet_address'],
            'to' => $this->config->get('blockchain')['withdraw_address'],
            'gasLimit' => Util::toHex(4612388),
            'gasPrice' => Util::toHex(10000000000),
            'data' => $this->eth->encodeContractMethod('complete(address,uint256,uint256,uint256)', [
                $request->getAddress(),
                Util::toHex((int) $request->getUserGuid()),
                Util::toHex($request->getGas()),
                Util::toHex((int) $request->getAmount()),
            ])
         ]);
    }

}
