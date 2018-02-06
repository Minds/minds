<?php

/**
 * Blockchain TDE controller
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;

class tde implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $cacher = Di::_()->get('Cache');

        if (!isset($pages[0])) {
            $pahes[0] = 'stats';
        }

        switch ($pages[0]) {
            case 'rates':
                $rates = $cacher->get('blockchain:tde:rates');

                if (!$rates) {
                    $rates = [
                        'eth' => Di::_()->get('Blockchain\TokenDistributionEvent')->rate(),
                        'last_update' => time()
                    ];

                    $cacher->set('blockchain:tde:rates', $rates, 60 * 60);
                }

                return Factory::response([
                    'rates' => $rates
                ]);
                break;
            case 'stats':
                $stats = $cacher->get('blockchain:tde:stats');

                if (!$stats) {
                    $token = Di::_()->get('Blockchain\Token');
                    $tde = Di::_()->get('Blockchain\TokenDistributionEvent');

                    $remaining = $tde->endTime() - time();
                    $remaining = floor($remaining / (60 * 60));

                    if ($remaining < 0) {
                        $remaining = 0;
                    }

                    $stats = [
                        'tokens' => $token->totalSupply(),
                        'raised' => $tde->raised(),
                        'remaining' => $remaining,
                        'last_update' => time()
                    ];

                    $cacher->set('blockchain:tde:stats', $stats, 5 * 60);
                }

                return Factory::response([
                    'stats' => $stats
                ]);
                break;
            case 'pre-register':
                Factory::isLoggedIn();

                return Factory::response([
                    'registered' => Di::_()->get('Blockchain\Preregistrations')
                        ->isRegistered(Session::getLoggedinUser())
                ]);
                break;
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
        Factory::isLoggedIn();

        switch ($pages[0]) {
            case 'pre-register':
                try {
                    $user = Session::getLoggedinUser();

                    return Factory::response([
                        'done' => Di::_()->get('Blockchain\Preregistrations')->register($user)
                    ]);
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }

                break;
        }

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
