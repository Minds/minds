<?php
/**
 * Manages reward withdrawals to the blockchain
 */
namespace Minds\Core\Rewards\Withdraw;

use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Manager
{

    /** @var \Minds\Core\Blockchain\Transactions\Manager */
    protected $txManager;

    /** @var Transactions $offChainTransactions */
    protected $offChainTransactions;

    /** @var Config $config */
    protected $config;

    /** @var Ethereum $eth */
    protected $eth;

    /** @var \Minds\Core\Rewards\Withdraw\Repository */
    protected $repo;

    public function __construct(
        $txManager = null,
        $offChainTransactions = null,
        $config = null,
        $eth = null,
        $withdrawRepository = null
    )
    {
        $this->txManager = $txManager ?: Di::_()->get('Blockchain\Transactions\Manager');
        $this->offChainTransactions = $offChainTransactions ?: Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
        $this->config = $config ?: Di::_()->get('Config');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->repo = $withdrawRepository ?: Di::_()->get('Rewards\Withdraw\Repository');
    }

    /**
     * Checks if a withdrawal has been made in the last 24 hours
     * @param $userGuid
     * @return boolean
     */
    public function check($userGuid)
    {
        $previousRequests = $this->repo->getList([
            'user_guid' => $userGuid,
            'contract' => 'withdraw',
            'from' => strtotime('-1 day')
        ]);

        return !isset($previousRequests) || !isset($previousRequests['withdrawals']) || count($previousRequests['withdrawals']) === 0;
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
            ->setAmount($request->getAmount())
            ->setWalletAddress($request->getAddress())
            ->setTimestamp($request->getTimestamp())
            ->setUserGuid($request->getUserGuid())
            ->setData([
                'amount' => $request->getAmount(),
                'gas' => $request->getGas(),
                'address' => $request->getAddress(),
            ]);

        $this->repo->add($request);
        $this->txManager->add($transaction);
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

        if (strtolower($request->getAddress()) != strtolower($transaction->getData()['address'])) {
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

        $this->offChainTransactions
            ->setUser($user)
            ->setType('withdrawal')
            //->setTx($request->getTx())
            ->setAmount((string) BigNumber::_($request->getAmount())->neg())
            ->create();

        $request->setCompleted(true);
        $this->repo->add($request);

        //now issue the transaction
        $res = $this->eth->sendRawTransaction($this->config->get('blockchain')['rewards_wallet_pkey'], [
            'from' => $this->config->get('blockchain')['rewards_wallet_address'],
            'to' => $this->config->get('blockchain')['withdraw_address'],
            'gasLimit' => BigNumber::_(4612388)->toHex(true),
            'gasPrice' => BigNumber::_(10000000000)->toHex(true),
            'data' => $this->eth->encodeContractMethod('complete(address,uint256,uint256,uint256)', [
                $request->getAddress(),
                BigNumber::_($request->getUserGuid())->toHex(true),
                BigNumber::_($request->getGas())->toHex(true),
                BigNumber::_($request->getAmount())->toHex(true),
            ])
         ]);
    }

}
