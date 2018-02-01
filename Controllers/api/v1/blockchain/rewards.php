<?php

/**
 * Crypto Rewards
 *
 * @author Mark
 */

namespace Minds\Controllers\api\v1\blockchain;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Rewards\Withdraw;
use Minds\Core\Rewards\Join;

class rewards implements Interfaces\Api
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
            case "balance":
                $balance = Di::_()->get('Rewards\Balance');
                $balance->setUser(Session::getLoggedinUser());

                $response = [
                    'balance' => $balance->get()
                ];
                break;
            case "ledger":
                $repo = Di::_()->get('Rewards\Repository');
                $result = $repo->getList([
                    'from' => $from,
                    'to' => $to,
                    'user_guid' => Session::getLoggedInUser()->guid,
                    'offset' => $offset
                ]);
                
                $response = [
                    'rewards' => Factory::exportable($result['rewards']),
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
            case 'verify':
                if (!isset($_POST['number'])) {
                    return Factory::response(['status' => 'error', 'message' => 'phone field is required']);
                }
                $number = $_POST['number'];

                try {
                    $join = new Join();
                    $join
                        ->setUser(Session::getLoggedInUser())
                        ->setNumber($number);

                    $secret = $join->verify();

                    $response['secret'] = $secret;
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ];
                }
                break;
            case 'confirm':
                if (!isset($_POST['number'])) {
                    return Factory::response(['status' => 'error', 'message' => 'phone field is required']);
                }

                $number = $_POST['number'];

                if (!isset($_POST['code'])) {
                    return Factory::response(['status' => 'error', 'message' => 'code field is required']);
                }
                $code = $_POST['code'];

                if (!isset($_POST['secret'])) {
                    return Factory::response(['status' => 'error', 'message' => 'code field is required']);
                }
                $secret = $_POST['secret'];

                $user = Session::getLoggedInUser();

                try {
                    $join = new Join();
                    $join
                        ->setUser($user)
                        ->setNumber($number)
                        ->setCode($code)
                        ->setSecret($secret)
                        ->confirm();
                    
                    $response['phone_number_hash'] = $user->getPhoneNumberHash();

                    Session::regenerate(false, $user);
                    //sync our change to our other sessions
                    (new Core\Data\Sessions())->syncAll($user->guid);
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Confirmation failed'
                    ];
                }
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
