<?php
/**
 * Minds Monetization Ledger
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1\monetization\service;

use Minds\Components\Controller;
use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments\Merchant;

class analytics extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $user = Core\Session::getLoggedInUser();

        $stripe = Core\Di\Di::_()->get('StripePayments');
        $merchant = (new Merchant)->setId($user->getMerchant()['id']);
        if (!$merchant->getId()) {
          return Factory::response([
            'status' => 'error',
            'message' => 'User is not a merchant'
          ]);
        }

        switch ($pages[0]) {
            case 'chart':

                $days = isset($_GET['days']) ? (int) $_GET['days'] : 14;

                $results = $stripe->getDailyBalance($merchant, [
                    'days' => $days
                ]);

                $rows = [];
                foreach ($results as $date => $values) {
                    $rows[] = [
                        date('n/d', strtotime($date)),
                        $values['net']
                    ];
                }

                $rows = $rows;

                return Factory::response([
                    'chart' => [
                        'title' => "Transactions ({$days} days ago - today)",
                        'columns' => [
                            ['label' => 'Date'],
                            ['label' => 'Amount', 'type' => 'currency']
                        ],
                        'rows' => $rows
                    ],
                ]);
                break;

            case 'list':
                $type = isset($_GET['type']) ? $_GET['type'] : 'payments';

                if($type == 'earnings') {
                  $results = $stripe->getTransactions($merchant, [
                      'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
                      'offset' => isset($_GET['offset']) ? $_GET['offset'] : '',
                      'orderIdPrefix' => isset($_GET['orderIdPrefix']) ? $_GET['orderIdPrefix'] : ''
                  ]);
                }

                if($type == 'payouts'){
                    $results = $stripe->getPayouts($merchant, [
                        'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
                        'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
                    ]);
                }

                $transactions = [];
                foreach ($results as $i => $record) {
                    $transactions[$i] = [
                        'id' => $record->id,
                        'ts' => $record->created,
                        'status' => $record->status,
                        'refunded' => $record->refunded,
                        'dispute' => $record->dispute,
                        'outcome' => $record->outcome,
                        'currency' => $record->currency,
                        'category' => explode('-', $record->metadata->orderId)[0],
                        'description' => $record->description ?: $record->type,
                        'amount' => $record->net / 100,
                        'json' => json_encode($record)
                    ];

                    if ($type == 'payouts') {
                      $transactions[$i]['amount'] = $record->amount / 100;
                      $transactions[$i]['account'] = [
                        'bank' => $record->bank_account->bank_name,
                        'last4' => $record->bank_account->last4
                      ];
                    }
                }

                return Factory::response([
                    'transactions' => $transactions,
                    'load-next' => $transactions ? end($transactions)['id'] : false
                ]);
        }

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
