<?php

/**
 * Blockchain Wire Events
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Contracts\MindsToken;
use Minds\Core\Blockchain\Util;
use Minds\Core\Di\Di;
use Minds\Core\Wire\Methods\Tokens;

class WireEvent implements BlockchainEventInterface
{
    public static $eventsMap = [
        '0xce785fa87dd60f986617d1c5e02218c5b233399cc29e9a326a41a76fabc95d66' => 'wireSent'
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

    public function wireSent($log)
    {
        $token = MindsToken::at(Di::_()->get('Config')->get('blockchain')['token_address']);

        $tx = $log['transactionHash'];
        list($sender, $receiver, $amount) = Util::parseData($log['data']);
        $amount = Util::toDec($amount) / (10 ** $token->getExtra()['decimals']);

        /** @var Tokens $wireMethod */
        $wireMethod = Di::_()->get('Wire\Method\Tokens');

        try {
            $wireMethod->checkAndSaveWire($tx, $sender, $receiver, $amount);
        } catch (\Exception $e) {
            // Catch race condition. Mining might be faster than /v1/wire request.
            sleep(2);

            try {
                $wireMethod->checkAndSaveWire($tx, $sender, $receiver, $amount);
            } catch (\Exception $e) {
                // Log?
            }
        }

    }
}
