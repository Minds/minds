<?php

/**
 * Blockchain Withdraw Events
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Contracts\MindsToken;
use Minds\Core\Blockchain\Util;
use Minds\Core\Di\Di;
use Minds\Core\Rewards\Withdraw;

class WithdrawEvent implements BlockchainEventInterface
{
    /** @var array $eventsMap */
    public static $eventsMap = [
        '0x97c9c5ff1001024e46522a71437e7254ea51d565969519322fea69afbbca41c4' => 'onRequest',
    ];

    /** @var Manager $manager */
    private $manager;

    public function __construct($manager = null)
    {
        $this->manager = $manager ?: Di::_()->get('Rewards\Withdraw\Manager');
    }

    /**
     * @return array
     */
    public function getTopics()
    {
        return array_keys(static::$eventsMap);
    }

    /**
     * @param $topic
     * @param array $log
     * @throws \Exception
     */
    public function event($topic, array $log)
    {
        $method = static::$eventsMap[$topic];

        if (method_exists($this, $method)) {
            $this->{$method}($log);
        } else {
            throw new \Exception('Method not found');
        }
    }

    public function onRequest($log)
    {
        $token = MindsToken::at(Di::_()->get('Config')->get('blockchain')['token_address']);

        $tx = $log['transactionHash'];
        list($address, $user_guid, $gas, $amount) = Util::parseData($log['data']);
        $user_guid = Util::toDec($user_guid);
        $gas = Util::toDec($gas);
        $amount = Util::toDec($amount);

        //double check the details of this transaction match with what the user actually requested
        $request = new Withdraw\Request();

        $request
            ->setTx($tx)
            ->setAddress($address)
            ->setUserGuid($user_guid)
            ->setGas($gas)
            ->setAmount($amount);

        $this->manager->complete($request);

    }
}
