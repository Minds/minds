<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Util;

class TokenSaleEvent implements BlockchainEventInterface
{
    public static $eventsMap = [
        '0x623b3804fa71d67900d064613da8f94b9617215ee90799290593e1745087ad18' => 'tokenPurchase'
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
    public function event($topic, array $log, $transaction)
    {
        $method = static::$eventsMap[$topic];

        if (method_exists($this, $method)) {
            $this->{$method}($log, $transaction);
        } else {
            throw new \Exception('Method not found');
        }
    }

    protected function tokenPurchase($log)
    {
        list($purchaser, $beneficiary, $value, $amount) = Util::parseData($log['data']);
        $value = Util::toDec($value);
        $amount = Util::toDec($amount);

        echo 'TOKEN PURCHASE' . PHP_EOL;
        var_dump([
            $purchaser, $beneficiary, $value, $amount
        ]);
    }
}
