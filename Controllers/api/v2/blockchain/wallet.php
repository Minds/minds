<?php

/**
 * Blockchain Wallet Controller
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;

class wallet implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        /** @var abstractCacher $cache */
        $cache = Di::_()->get('Cache');

        $response = [];

        $query = isset($pages[0]) ? $pages[0] : 'address';

        switch ($query) {
            case 'address':
                $response['wallet'] = [
                    'address' => Session::getLoggedinUser()->getEthWallet()
                ];
                break;
            case 'balance':
                $onChainBalance = Di::_()->get('Blockchain\Wallets\OnChain\Balance');
                $onChainBalance->setUser(Session::getLoggedinUser());
                $onChainBalanceVal = (double) $onChainBalance->get();

                $offChainBalance = Di::_()->get('Blockchain\Wallets\OffChain\Balance');
                $offChainBalance->setUser(Session::getLoggedinUser());
                $offChainBalanceVal = (double) $offChainBalance->get();
                
               
                $balance = $onChainBalanceVal + $offChainBalanceVal;

                $todayWiresBalance = -$offChainBalance->getByContract('offchain:wire', strtotime('today 00:00'), true);
                $wireCap = (100 * (10 ** 18)) - $todayWiresBalance;

                $response = [
                    'addresses' => [
                        [
                            'address' => Session::getLoggedinUser()->getEthWallet(),
                            'label' => 'Receiver',
                            'balance' => (double) $onChainBalanceVal,
                        ],
                        [
                            'address' => 'offchain',
                            'label' => 'OffChain',
                            'balance' => (double) $offChainBalanceVal,
                        ]
                    ],
                    'balance' => (double) $balance,
                    'wireCap' => $wireCap,
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
        if (!isset($_POST['address'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Address is required'
            ]);
        }

        if ($_POST['address'] !== '' && !preg_match('/^0x[a-fA-F0-9]{40}$/', $_POST['address'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid address value'
            ]);
        }

        $user = Session::getLoggedinUser();
        $user->setEthWallet($_POST['address']);
        $user->save();

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
