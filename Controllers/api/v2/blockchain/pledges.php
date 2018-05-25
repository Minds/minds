<?php
/**
 * Tokens Pledge
 *
 * @author mark
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core\Util\BigNumber;
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
        $walletAddress = isset($_POST['wallet_address']) ? $_POST['wallet_address'] : '';
        $maxAmount = Di::_()->get('Config')->get('blockchain')['max_pledge_amount'] ?: 1800;

        if (!Session::getLoggedInUser()->getPhoneNumberHash()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must confirm your phone number before entering a pledge',
            ]);
        }

        if ($amount < 0.01 || $amount > $maxAmount) {
            return Factory::response([
                'status' => 'error',
                'message' => "You must pledge between 0.01 and {$maxAmount} ETH",
            ]);
        }

        if (!$walletAddress || stripos($walletAddress, '0x') !== 0) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid wallet address',
            ]);
        }

        $weiAmount = BigNumber::toPlain($amount, 18);

        $pledge = new Pledge();
        $pledge
            ->setPhoneNumberHash(Session::getLoggedInUser()->getPhoneNumberHash())
            ->setUserGuid(Session::getLoggedInUser()->guid)
            ->setAmount((string) $weiAmount)
            ->setTimestamp(time())
            ->setWalletAddress($walletAddress)
            ->setStatus('review');

        $manager = Di::_()->get('Blockchain\Pledges\Manager');
        $manager->add($pledge);

        return Factory::response([
            'pledge' => $pledge->export(),
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
