<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Util;
use Minds\Core\Util\BigNumber;

class TokenSaleEvent implements BlockchainEventInterface
{
    public static $eventsMap = [
        '0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861' => 'onTokenPurchase',
        'blockchain:fail' => 'purchaseFail',
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

    protected function onTokenPurchase($log, $transaction)
    {
        list($purchaser, $amount) = Util::parseData($log['data'], [Util::ADDRESS, Util::ADDRESS, Util::NUMBER, Util::NUMBER]);
        $amount = (string) BigNumber::fromHex($amount);

        if ($amount != (string) $transaction->getAmount()) {
            return; //backend amount does not equal event amount
        }

        $manager = new Purchase\Manager();
        $purchase = $manager->getPurchase($transaction->getData()['phone_number_hash']);

        if (!$purchase) {
            return; //purchase not found
        }

        //is the requested amount below what has already been recorded
        if ($transaction->getAmount() > $purchase->getUnissuedAmount()) {
            return; //requested more than can issue
        }

        //is the request below the threshold?
        if ($purchase->getUnissuedAmount() > $manager->getAutoIssueCap()) {
            return; //mark as failed
        }

        //issue the tokens
        $manager->issue($purchase);
    }

    public function purchaseFail($log, $transaction) {
        if ($transaction->getContract() !== 'purchase') {
            throw new \Exception("Failed but not a purchase");
            return;
        }

        $transaction->setFailed(true);

        $this->txRepository->update($transaction, [ 'failed' ]);
    }
}
