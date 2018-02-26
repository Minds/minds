<?php

/**
 * Token Manager
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class Token
{
    protected $manager;
    protected $client;

    protected $tokenAddress;
    protected $tokenDecimals;

    /**
     * Token constructor.
     * @param null $config
     * @throws \Exception
     */
    public function __construct($manager = null, $client = null)
    {
        $this->manager = $manager ?: Di::_()->get('Blockchain\Manager');
        $this->client = $client ?: Di::_()->get('Blockchain\Services\Ethereum');

        if (!$contract = $this->manager->getContract('token')) {
            throw new \Exception('No token set');
        }

        $this->tokenAddress = $contract->getAddress();
        $this->tokenDecimals = $contract->getExtra()['decimals'] ?: 18;
    }

    /**
     * Gets an account's balance of token
     * @param $account
     * @return string
     * @throws \Exception
     */
    public function balanceOf($account)
    {
        $result = $this->client->call($this->tokenAddress, 'balanceOf(address)', [ $account ]);

        return (string) BigNumber::fromHex($result);
    }

    /**
     * Gets the total supply of token
     * @return double
     */
    public function totalSupply()
    {
        $result = $this->client->call($this->tokenAddress, 'totalSupply()', []);

        return $this->fromTokenUnit(BigNumber::fromHex($result));
    }

    /**
     * @param $amount
     * @return string
     * @throws \Exception
     */
    public function toTokenUnit($amount)
    {
        return (string) BigNumber::toPlain($amount, $this->tokenDecimals);
    }

    /**
     * @param $amount
     * @return float
     * @throws \Exception
     */
    public function fromTokenUnit($amount)
    {
        return (string) BigNumber::fromPlain($amount, $this->tokenDecimals);
    }
}
