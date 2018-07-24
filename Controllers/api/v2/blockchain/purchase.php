<?php
/**
 * Tokens Purchase
 *
 * @author mark
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core\Util\BigNumber;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Blockchain\Purchase\Purchase as PurchaseModel;

class purchase implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
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
        $walletAddress = isset($_POST['wallet_address']) ? $_POST['wallet_address'] : '';
        $tx = isset($_POST['tx']) ? $_POST['tx'] : '';

        if (!Session::getLoggedInUser()->getPhoneNumberHash()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must confirm your phone number before entering a pledge',
            ]);
        }

        /*if ($amount < 0.01 || $amount > $maxAmount) {
            return Factory::response([
                'status' => 'error',
                'message' => "You must pledge between 0.01 and {$maxAmount} ETH",
            ]);
        }*/

        if (!$walletAddress || stripos($walletAddress, '0x') !== 0) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid wallet address',
            ]);
        }

        $weiAmount = BigNumber::toPlain($amount, 18)->mul(350); //convert to tokens

        $purchase = new PurchaseModel();
        $purchase
            ->setPhoneNumberHash(Session::getLoggedInUser()->getPhoneNumberHash())
            ->setUserGuid(Session::getLoggedInUser()->guid)
            ->setTx($tx)
            ->setRequestedAmount((string) $weiAmount)
            ->setTimestamp(time())
            ->setWalletAddress($walletAddress)
            ->setStatus('purchased');

        $manager = Di::_()->get('Blockchain\Purchase\Manager');
        $manager->purchase($purchase);

        return Factory::response([
            'purchase' => $purchase->export(),
        ]);
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
