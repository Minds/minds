<?php

/**
 * Blockchain Peer Boost Events
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Contracts\MindsToken;
use Minds\Core\Blockchain\Util;
use Minds\Core\Di\Di;
use Minds\Core\Wire\Methods\Tokens;

class PeerBoostEvent implements BlockchainEventInterface
{
    public static $eventsMap = [
        '0xca9b3656fa3420c033337af0b3935a07b180951c1da4ca108992c8b8c6a0b522' => 'peerBoostSent',
        '0x91753a5b2a6e1eec8cbbe8af7a2d298a9976547d45a2b9e24144d53f50aea954' => 'peerBoostAccepted',
        '0x7626654009627c066216e658b51dce56d55165d7a27d1fc742d77e6909449bfc' => 'peerBoostRejected',
        '0xfdca18ab1356c678985966fc2be26accaaa6a8558926ea2a63fcd6f346f4340d' => 'peerBoostRevoked',
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

    public function peerBoostSent($log)
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

    public function peerBoostAccepted($log)
    {
    }

    public function peerBoostRejected($log)
    {
    }

    public function peerBoostRevoked($log)
    {
    }
}
