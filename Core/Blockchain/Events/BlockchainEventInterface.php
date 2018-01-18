<?php
/**
 * Blockchain Event Interface
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Events;


interface BlockchainEventInterface
{
    /**
     * @return array
     */
    public function getTopics();

    /**
     * @param $topic
     * @param array $log
     * @param Transaction $transaction
     * @return void
     */
    public function event($topic, array $log, $transaction);
}
