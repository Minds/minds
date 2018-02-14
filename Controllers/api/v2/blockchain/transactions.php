<?php

/**
 * Crypto transactions
 *
 * @author Mark
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Rewards\Withdraw;
use Minds\Core\Rewards\Join;

class transactions implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $cacher = Di::_()->get('Cache');

        $from = isset($_GET['from']) ? $_GET['from'] : strtotime('midnight -7 days') * 1000;
        $to = isset($_GET['to']) ? $_GET['to'] : time() * 1000;
        $offset = $_GET['offset'] ? $_GET['offset'] : '';

        $response = [];

        switch ($pages[0]) {
            case "ledger":
                $repo = Di::_()->get('Blockchain\Transactions\Repository');
                $result = $repo->getList([
                    'timestamp' => [
                        'gte' => $from,
                        //'lte' => $to,
                    ],
                    'wallet_addresses' => [
                        'offchain',
                    ],
                    'user_guid' => Session::getLoggedInUser()->guid,
                    'offset' => $offset
                ]);
                
                $response = [
                    'transactions' => Factory::exportable($result['transactions']),
                    'load-next' => base64_encode($result['token'])
                ];
                break;
            case "withdrawals":
                $repo = Di::_()->get('Rewards\Withdraw\Repository');
                $result = $repo->getList([
                    'user_guid' => Session::getLoggedInUser()->guid,
                    'from' => $from,
                    'to' => $to,
                    'offset' => $offset
                ]);

                $response = [
                    'withdrawals' => Factory::exportable($result['withdrawals']),
                    'load-next' => base64_encode($result['token'])
                ];
                break;
        }
        
        return Factory::response($response);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        Factory::isLoggedIn();
        $response = [];

        switch ($pages[0]) {
            case "withdraw":
                $request = new Withdraw\Request();
                $request->setTx($_POST['tx'])
                    ->setUserGuid(Session::getLoggedInUser()->guid)
                    ->setAddress($_POST['address'])
                    ->setTimestamp(time())
                    ->setGas($_POST['gas'])
                    ->setAmount($_POST['amount']);

                $manager = new Withdraw\Manager();
                $manager->request($request);

                $response['done'] = true;
                $response['entity'] = $request->export();
                break;
            case 'spend':
                if (!$_POST['type']) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Type is required'
                    ]);
                }

                if (!$_POST['amount'] || !is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Amount should be a positive number'
                    ]);
                }

                /** @var Core\Blockchain\Wallets\OffChain\Transactions $transactions */
                $transactions = Di::_()->get('Blockchain\Wallets\OffChain\Transactions');

                $amount = $transactions->toWei((double) $_POST['amount']);

                $transactions
                    ->setUser(Session::getLoggedinUser())
                    ->setType($_POST['type'])
                    ->setAmount(-$amount);

                $transaction = $transactions->create();

                $response = [
                    'txHash' => $transaction->getTx()
                ];

                break;
        }

        return Factory::response($response);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        Factory::isLoggedIn();

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        Factory::isLoggedIn();

        return Factory::response([]);
    }
}
