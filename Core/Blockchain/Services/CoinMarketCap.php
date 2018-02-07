<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Services;

use Minds\Core\Di\Di;
use Minds\Core\Http\Curl\Json\Client;

class CoinMarketCap implements RatesInterface
{
    /** @var Client $http */
    protected $http;

    /** @var string $currency */
    protected $currency;

    /**
     * CoinMarketCap constructor.
     * @param null $http
     */
    public function __construct($http = null)
    {
        $this->http = $http ?: Di::_()->get('Http\Json');
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
        if (!$this->currency) {
            throw new \Exception('Currency is required');
        }

        $rates = $this->request("v1/ticker/{$this->currency}");

        return (double) $rates['price_usd'];
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
                CURLOPT_FOLLOWLOCATION => true
            ]
        ]);

        if (!is_array($response) || !isset($response[0])) {
            throw new \Exception('Invalid CoinMarketCap response');
        }

        return $response[0];
    }
}
