<?php

/**
 * Blockchain Market Rate API
 *
 * @author eiennohi
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\cache\Redis;
use Minds\Core\Di\Di;
use Minds\Core\Http\Curl\Client;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;


class rate implements Interfaces\Api
{
    public function get($pages)
    {
        /** @var Redis $cacher */
        $cacher = \Minds\Core\Data\cache\factory::build();

        $rate = $cacher->get('tokens:rate');

        if (!$rate) {
            try {
                $rate = $this->getRate();
            } catch (\Exception $e) {
                return Factory::response(['status' => 'error', 'message' => $e->getMessage()]);
            }

            $cacheTTL = Di::_()->get('Config')->get('blockchain')['token_rate_cache_ttl'];

            $cacher->set('tokens:rate', ['rate' => $rate], $cacheTTL * 60);
        }


        return Factory::response(['rate' => is_numeric($rate) ? $rate : floatval($rate['rate']) ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getRate()
    {
        /** @var Client $http */
        $http = Di::_()->get('Http');

        $res = $http->get('https://api.coinmarketcap.com/v1/ticker/EOS/?convert=USD');
        $res = json_decode($res);

        $errorMessage = 'There was an error while querying Tokens rate';

        if (is_array($res)) {
            $res = $res[0];
        } else {
            throw new \Exception($errorMessage);
        }

        if (!$res->price_usd) {
            throw new \Exception($errorMessage);
        }

        return $res->price_usd;
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}