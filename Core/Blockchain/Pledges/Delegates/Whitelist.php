<?php

/**
 * Pledge Whitelist Delegate
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Pledges\Delegates;

use Minds\Core\Blockchain\Pledges\Pledge;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Util\BigNumber;

class Whitelist
{
    /** @var Ethereum */
    protected $ethereumClient;

    /**
     * Whitelist constructor.
     * @param null $ethereumClient
     */
    public function __construct($ethereumClient = null)
    {
        $this->ethereumClient = $ethereumClient ?: Di::_()->get('Blockchain\Services\Ethereum');
    }

    /**
     * @param Pledge $pledge
     * @return mixed
     * @throws \Exception
     */
    public function add(Pledge $pledge)
    {
        $txHash = $this->ethereumClient->sendRawTransaction($this->config->get('blockchain')['wallet_pkey'], [
            'from' => $this->config->get('blockchain')['wallet_address'],
            'to' => $this->config->get('blockchain')['token_distribution_event_address'],
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => $this->ethereumClient->encodeContractMethod('pledge(address, uint256)', [
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
