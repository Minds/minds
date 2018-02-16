<?php

/**
 * Blockchain Market Rate API
 *
 * @author eiennohi
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Blockchain\Services\RatesInterface;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;


class rate implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $currencyId = isset($pages[0]) ? $pages[0] : 'ethereum';

        if ($currencyId === 'tokens') {
            $currencyId = Di::_()->get('Config')->get('blockchain')['token_symbol'];
        }

        /** @var RatesInterface $rates */
        $rates = Di::_()->get('Blockchain\Rates');

        $rate = $rates
            ->setCurrency($currencyId)
            ->get();

        if (!$rate) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Cannot get rates'
            ]);
        }

        return Factory::response([
            'rate' => $rate
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
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
