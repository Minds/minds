<?php

/**
 * Poloniex Service
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain\Services;

use Minds\Core\Di\Di;
use Minds\Core\Http\Curl\Json\Client;

class Poloniex
{
    const VALID_RESOLUTIONS = [300, 900, 1800, 7200, 14400, 86400];

    /**
     * Poloniex constructor.
     * @param Http\Json $http
     */
    public function __construct($http = null)
    {
        $this->http = $http ?: Di::_()->get('Http\Json');
    }

    /**
     * Get chart data
     *
     * @param integer $from unix timestamp
     * @param integer $to unix timestamp
     * @param integer $resolution in seconds valid: 300, 900, 1800, 7200, 14400, and 86400
     * @return void
     */
    public function getChartData($from, $to, $resolution)
    {
        if (!in_array((int) $resolution, self::VALID_RESOLUTIONS)) {
            throw new \Exception("Poloniex: Invalid resolution $resolution");
        }

        return $this->request("command=returnChartData&currencyPair=USDT_ETH&start=$from&end=$to&period=$resolution");
    }

    /**
     * @param string $endpoint
     * @return array
     * @throws \Exception
     */
    protected function request($endpoint)
    {
        $response = $this->http->get("https://poloniex.com/public?$endpoint");

        if (!is_array($response)) {
            throw new \Exception('Invalid response');
        }
         return $response;
    }
}