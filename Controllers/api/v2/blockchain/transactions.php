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
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
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
        $user = Session::getLoggedInUser();

        if (isset($_GET['remote']) && $_GET['remote']) {
            if (!Session::isAdmin()) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Insufficient permissions'
                ]);
            }

            $user = new User(strtolower($_GET['remote']));

            if (!$user || !$user->guid) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'User not found'
                ]);
            }
        }

        $from = isset($_GET['from']) ? $_GET['from'] : strtotime('midnight -7 days') * 1000;
        $to = isset($_GET['to']) ? $_GET['to'] : time() * 1000;
        $offset = $_GET['offset'] ? $_GET['offset'] : '';
        $contract = isset($_GET['contract']) && $_GET['contract'] ? $_GET['contract'] : null;
        $address = isset($_GET['address']) && $_GET['address'] ? $_GET['address'] : null;

        $response = [];

        switch ($pages[0]) {
            case "ledger":
                /** @var Core\Blockchain\Transactions\Repository $repo */
                $repo = Di::_()->get('Blockchain\Transactions\Repository');
                $opts = [
                    'timestamp' => [
                        'gte' => $from,
                        'lte' => $to,
                    ],
                    'user_guid' => $user->guid,
                    'offset' => $offset,
                ];


                if ($address) {
                    $opts['wallet_address'] = $address;
                }

                if ($contract) {
                    $opts['contract'] = $contract;
                }

                $result = $repo->getList($opts);

                $response = [
                    // 'addresses' => $addresses,
                    'contract' => $contract,
                    'transactions' => Factory::exportable($result['transactions']),
                    'load-next' => base64_encode($result['token'])
                ];
                break;
            case "withdrawals":
                $repo = Di::_()->get('Rewards\Withdraw\Repository');
                $result = $repo->getList([
                    'user_guid' => $user->guid,
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
            case "can-withdraw":
                $manager = new Withdraw\Manager();
                $response['canWithdraw'] = $manager->check(Session::getLoggedinUser()->guid);
                break;
            case "withdraw":
                $request = new Withdraw\Request();
                $request->setTx($_POST['tx'])
                    ->setUserGuid(Session::getLoggedInUser()->guid)
                    ->setAddress($_POST['address'])
                    ->setTimestamp(time())
                    ->setGas($_POST['gas'])
                    ->setAmount((string) BigNumber::_($_POST['amount']));

                $manager = new Withdraw\Manager();
                try {
                    $manager->request($request);

                    $response['done'] = true;
                    $response['entity'] = $request->export();
                } catch (\Exception $e) {
                    $response = ['status' => 'error', 'message' => $e->getMessage()];
                }
                break;
            case 'spend':
                if (!$_POST['type']) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Type is required'
                    ]);
                }

                $amount = BigNumber::_($_POST['amount']);

                if ($amount->lte(0)) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Amount should be a positive number'
                    ]);
                }

                /** @var Core\Blockchain\Wallets\OffChain\Transactions $transactions */
                $transactions = Di::_()->get('Blockchain\Wallets\OffChain\Transactions');

                $transactions
                    ->setUser(Session::getLoggedinUser())
                    ->setType($_POST['type'])
                    ->setAmount((string) BigNumber::_($amount)->neg());

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
