<?php

/**
 * Minds Blockchain Events
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Blockchain\Events\BlockchainEventInterface;
use Minds\Core\Blockchain\Events\BoostEvent;
use Minds\Core\Blockchain\Events\TokenSaleEvent;
use Minds\Core\Blockchain\Events\WireEvent;
use Minds\Core\Blockchain\Events\WithdrawEvent;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;

class Events
{
    protected static $handlers = [
        TokenSaleEvent::class,
        WireEvent::class,
        BoostEvent::class,
        WithdrawEvent::class
    ];

    public function register()
    {
        Dispatcher::register('blockchain:listen', 'all', function (Event $event) {
            $topicsMap = [];

            foreach (static::$handlers as $handlerClass) {
                /** @var BlockchainEventInterface $handler */
                $handler = new $handlerClass();
                $topics = $handler->getTopics();

                if (!is_array($topics)) {
                    $topics = [ $topics ];
                }

                foreach ($topics as $topic) {
                    if (!isset($topicsMap[$topic])) {
                        $topicsMap[$topic] = [];
                    }

                    $topicsMap[$topic][] = $handlerClass;
                }
            }

            $event->setResponse($topicsMap);
        });
    }
}
