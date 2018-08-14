<?php

/**
 * Blockchain Wallet Controller
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Blockchain\Wallets\OnChain\Incentive;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core\Util\BigNumber;
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
                $onChainBalanceVal = BigNumber::_($onChainBalance->get());

                $offChainBalance = Di::_()->get('Blockchain\Wallets\OffChain\Balance');
                $offChainBalance->setUser(Session::getLoggedinUser());
                $offChainBalanceVal = BigNumber::_($offChainBalance->get());
                $offchainAvailableVal = BigNumber::_($offChainBalance->getAvailable());

                $balance = $onChainBalanceVal->add($offChainBalanceVal);

                $wireCap = Di::_()->get('Blockchain\Wallets\OffChain\Cap')
                    ->setUser(Session::getLoggedinUser())
                    ->setContract('wire')
                    ->allowance();

                $boostCap = Di::_()->get('Blockchain\Wallets\OffChain\Cap')
                    ->setUser(Session::getLoggedinUser())
                    ->setContract('boost')
                    ->allowance();

                $response = [
                    'addresses' => [
                        [
                            'address' => Session::getLoggedinUser()->getEthWallet(),
                            'label' => 'Receiver',
                            'balance' => (string) $onChainBalanceVal,
                        ],
                        [
                            'address' => 'offchain',
                            'label' => 'OffChain',
                            'balance' => (string) $offChainBalanceVal,
                            'available' => (string) $offchainAvailableVal,
                        ]
                    ],
                    'balance' => (string) $balance,
                    'wireCap' => (string) $wireCap,
                    'boostCap' => (string) $boostCap
                ];

                if (!Session::getLoggedinUser()->getPhoneNumberHash()) {
                    $testnetBalance = Di::_()->get('Blockchain\Wallets\OffChain\TestnetBalance');
                    $testnetBalance->setUser(Session::getLoggedinUser());
                    $response['testnetBalance'] = $testnetBalance->get();
                }

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
