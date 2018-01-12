<?php

/**
 * Blockchain Peer Boost Events
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Util;
use Minds\Core\Di\Di;

class BoostEvent implements BlockchainEventInterface
{
    public static $eventsMap = [
        '0x68170a430a4e2c3743702c7f839f5230244aca61ed306ec622a5f393f9559040' => 'boostSent',
        '0xd7ccb5dc8647fd89286a201b04b5e65fb7b5e281603e972695fd35f52bbd244b' => 'boostAccepted',
        '0xc43f9053be9f0ee374d3f8eb929d2e0aa990d33a7d4a51423cb715228d39ab89' => 'boostRejected',
        '0x0b869ea800008714ae430dc6c4e12a2c880d50fb92937d51a4b223af34040971' => 'boostRevoked',
    ];

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

    public function boostSent($log)
    {
        $tx = $log['transactionHash'];
        list($guid) = Util::parseData($log['data']);
        $guid = Util::toDec($guid);

        try {
            Di::_()->get('Boost\Pending')->resolve($tx, $guid);
        } catch (\Exception $e) {
            // Catch race condition. Mining might be faster than /v1/boost or /v1/boost/peer request.
            sleep(2);

            try {
                Di::_()->get('Boost\Pending')->resolve($tx, $guid);
            } catch (\Exception $e) {
                // Log?
            }
        }
    }

    public function boostAccepted($log)
    {
    }

    public function boostRejected($log)
    {
    }

    public function boostRevoked($log)
    {
    }
}
