<?php

/**
 * Purchase Issue
 *
 * @author Mark
 */

namespace Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Config;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class IssueTokens
{
    /** @var Ethereum */
    protected $ethereumClient;

    /**
     * Whitelist constructor.
     * @param null $config
     * @param null $ethereumClient
     */
    public function __construct($config = null, $ethereumClient = null, $txRepository = null)
    {
        $this->config = $config ?: new Config();
        $this->ethereumClient = $ethereumClient ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->txRepository = $txRepository ?: Di::_()->get('Blockchain\Transactions\Repository');
    }

    /**
     * @param Purchase $purchase
     * @return mixed
     * @throws \Exception
     */
    public function issue(Purchase $purchase)
    {
        $this->ethereumClient->useConfig('pledge');
        $this->config->setKey('pledge');

        $config = $this->config->get();

        $txHash = $this->ethereumClient->sendRawTransaction($config['contracts']['token_sale_event']['wallet_pkey'], [
            'from' => $config['contracts']['token_sale_event']['wallet_address'],
            'to' => $config['contracts']['token_sale_event']['contract_address'],
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => $this->ethereumClient->encodeContractMethod('issue(address,uint256)', [
                $purchase->getWalletAddress(),
                BigNumber::_($purchase->getUnissuedAmount())->toHex(true),
            ])
        ]);

        if (!$txHash) {
            throw new \Exception('Cannot retrieve Blockchain Tx address');
        }

        /*$transaction = new Transaction(); 
        $transaction
            ->setUserGuid($purchase->getUserGuid())
            ->setWalletAddress($purchase->getWalletAddress())
            ->setTimestamp(time())
            ->setTx($txHash)
            ->setAmount((string) $purchase->getUnIssuedAmount())
            ->setContract('issued_purchase')
            ->setCompleted(true);
    
        $this->txRepository->add($transaction);
        return $transaction;*/
    }
}
