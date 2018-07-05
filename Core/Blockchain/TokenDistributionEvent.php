<?php

/**
 * Token Distribution Event Manager
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class TokenDistributionEvent
{
    /** @var Manager */
    protected $manager;
    /** @var Ethereum */
    protected $client;

    protected $tokenDistributionEventAddress;

    /**
     * TokenDistributionEvent constructor.
     * @param null $manager
     * @param null $client
     * @throws \Exception
     */
    public function __construct($manager = null, $client = null)
    {
        $this->manager = $manager ?: Di::_()->get('Blockchain\Manager');
        $this->client = $client ?: Di::_()->get('Blockchain\Services\Ethereum');

        if (!$contract = $this->manager->getContract('token_distribution_event')) {
            throw new \Exception('No token distribution event set');
        }

        $this->tokenDistributionEventAddress = $contract->getAddress();
    }

    /**
     * Gets the token <-> eth exchange rate
     * @return string
     * @throws \Exception
     */
    public function rate()
    {
        $result = $this->client->call($this->tokenDistributionEventAddress, 'rate()', []);

        return (string) BigNumber::fromHex($result);
    }

    /**
     * Gets the total of ETH raised
     * @return double
     * @throws \Exception
     */
    public function raised()
    {
        $result = $this->client->call($this->tokenDistributionEventAddress, 'weiRaised()', []);

        return BigNumber::fromPlain(BigNumber::fromHex($result), 18)->toDouble();
    }

    /**
     * Gets the end time of the event
     * @return double
     * @throws \Exception
     */
    public function endTime()
    {
        $result = $this->client->call($this->tokenDistributionEventAddress, 'endTime()', []);

        return BigNumber::fromHex($result)->toInt();
    }
}
