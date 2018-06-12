<?php

/**
 * Pledge Whitelist Delegate
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Pledges\Delegates;

use Minds\Core\Blockchain\Config;
use Minds\Core\Blockchain\Pledges\Pledge;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class TokenSaleEventPledge
{
    /** @var Ethereum */
    protected $ethereumClient;

    /**
     * Whitelist constructor.
     * @param null $config
     * @param null $ethereumClient
     */
    public function __construct($config = null, $ethereumClient = null)
    {
        $this->config = $config ?: new Config();
        $this->ethereumClient = $ethereumClient ?: Di::_()->get('Blockchain\Services\Ethereum');
    }

    /**
     * @param Pledge $pledge
     * @return mixed
     * @throws \Exception
     */
    public function add(Pledge $pledge)
    {
        $this->ethereumClient->useConfig('pledge');
        $this->config->setKey('pledge');

        $config = $this->config->get();

        $txHash = $this->ethereumClient->sendRawTransaction($config['wallet_pkey'], [
            'from' => $config['wallet_address'],
            'to' => $config['token_distribution_event_address'],
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => $this->ethereumClient->encodeContractMethod('pledge(address,uint256)', [
                $pledge->getWalletAddress(),
                BigNumber::_($pledge->getAmount())->toHex(true),
            ])
        ]);

        if (!$txHash) {
            throw new \Exception('Cannot retrieve Blockchain Tx address');
        }

        return $txHash;
    }
}
