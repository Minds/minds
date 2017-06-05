<?php
/**
 * Minds Monetization
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\monetization;

use Minds\Components\Controller;
use Minds\Core;
use Minds\Core\Config;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class revenue extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $user = Core\Session::getLoggedInUser();
        $stripe = Core\Di\Di::_()->get('StripePayments');

        $merchant = $stripe->getMerchant($user->getMerchant()['id']);
        $volume = $stripe->getGrossVolume($merchant);
        $payouts = $stripe->getTotalPayouts($merchant);
        $balance = $stripe->getTotalBalance($merchant);

        switch($pages[0]){
            case 'overview':
                return Factory::response([
                    'total' => $volume,
                    'payouts' => $payouts,
                    'balance' => $balance
                ]);
                break;
        }

        return Factory::response([]);
    }


    public function post($pages)
    {

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
