<?php

/**
 * Token Manager
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Di\Di;

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
        $this->tokenDecimals = $contract->getExtra()['decimals'];
    }

    /**
     * Gets an account's balance of token
     * @param $account
     * @return double
     */
    public function balanceOf($account)
    {
        $result = $this->client->call($this->tokenAddress, 'balanceOf(address)', [ $account ]);

        return (double) Util::toDec($result);
    }

    /**
     * Gets the total supply of token
     * @return double
     */
    public function totalSupply()
    {
        $result = $this->client->call($this->tokenAddress, 'totalSupply()', []);

        return $this->fromTokenUnit(Util::toDec($result));
    }

    /**
     * @param $amount
     * @return float
     */
    public function toTokenUnit($amount)
    {
        return (double) ($amount * (10 ** $this->tokenDecimals));
    }

    /**
     * @param $amount
     * @return float
     */
    public function fromTokenUnit($amount)
    {
        return (double) ($amount / (10 ** $this->tokenDecimals));
    }
}
