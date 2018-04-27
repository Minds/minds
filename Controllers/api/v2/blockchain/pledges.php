<?php
/**
 * Tokens Pledge
 *
 * @author mark
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Blockchain\Pledges\Pledge;

class pledges implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $cacher = Di::_()->get('Cache');

        $manager = Di::_()->get('Blockchain\Pledges\Manager');
        $sums = Di::_()->get('Blockchain\Pledges\Sums');

        $response = [
            'amount' => $sums->getTotalAmount(),
            'count' => $sums->getTotalCount(),
            'pledged' => $manager->getPledgedAmount(Session::getLoggedInUser()),
        ];

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

        $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;
        $wallet_address = isset($_POST['wallet_address']) ? $_POST['wallet_address'] : '';

        if (!Session::getLoggedInUser()->getPhoneNumberHash()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must confirm your phone number before entering a pledge',
            ]);
        }

        if ($amount < 0 || $amount > 1000000) {
            return Factory::response([
                'status' => 'error',
                'message' => "Invalid amount sent",
            ]);
        }

        $pledge = new Pledge();
        $pledge
            ->setPhoneNumberHash(Session::getLoggedInUser()->getPhoneNumberHash())
            ->setUserGuid(Session::getLoggedInUser()->guid)
            ->setAmount($_POST['amount'])
            ->setTimestamp(time())
            ->setWalletAddress($wallet_address);

        $manager = Di::_()->get('Blockchain\Pledges\Manager');
        $manager->add($pledge);

        return Factory::response([]);
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
