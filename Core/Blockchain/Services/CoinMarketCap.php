<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Services;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\cache\factory as CacheFactory;
use Minds\Core\Di\Di;
use Minds\Core\Http\Curl\Json\Client;

class CoinMarketCap implements RatesInterface
{
    /** @var Client $http */
    protected $http;

    /** @var abstractCacher */
    protected $cacher;

    /** @var string $currency */
    protected $currency;

    /**
     * CoinMarketCap constructor.
     * @param null $http
     */
    public function __construct($http = null, $cacher = null)
    {
        $this->http = $http ?: Di::_()->get('Http\Json');
        $this->cacher = $cacher ?: Di::_()->get('Cache');
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     * @throws \Exception
     */
    public function get()
    {

        return 0.25; //hard code for testnet

        if (!$this->currency) {
            throw new \Exception('Currency is required');
        }

        $cacheKey = "blockchain:cmc:rate:{$this->currency}";

        if ($rate = $this->cacher->get($cacheKey)) {
            return unserialize($rate);
        }

        $rates = $this->request("v1/ticker/{$this->currency}");
        $rate = (double) $rates['price_usd'];

        $this->cacher->set($cacheKey, serialize($rate), 15 * 60);

        return $rate;
    }

    /**
     * @param string $endpoint
     * @return array
     * @throws \Exception
     */
    protected function request($endpoint)
    {
        $response = $this->http->get("https://api.coinmarketcap.com/{$endpoint}", [
            'curl' => [
                'CURLOPT_FOLLOWLOCATION' => true
            ]
        ]);

        if (!is_array($response) || !isset($response[0])) {
            throw new \Exception('Invalid CoinMarketCap response');
        }

        return $response[0];
    }
}
