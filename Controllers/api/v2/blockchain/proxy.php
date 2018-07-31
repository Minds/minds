<?php

/**
 * Blockchain Infura Proxy
 *
 * @author Martin Santangelo
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;

/**
 * Infura proxy controller
 */
class proxy implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /** @var Core\Http\Curl\Client */
    private $http;

    /** @var string */
    private $url;

    /** @var array */
    private $options = [
        'headers' => ['Content-Type: application/json']
    ];

    /**
     * Contructor
     * @param Core\Http\Curl\Client $http
     * @param Core\Config\Config $config
     */
    function __construct($http = null, $config = null) {
        $this->http = $http ?: Di::_()->get('Http');
        $config = $config ?: Di::_()->get('Config');

        $blockchainConfig = $config->get('blockchain');

        $this->url = $blockchainConfig['proxy_rpc_endpoint'];
    }

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $endpoint = implode($pages, '/');
        try {
            $output = $this->http->get($this->url . $endpoint . $_SERVER['QUERY_STRING'], $this->options);
            $this->send($output);
        } catch (\Exception $e) {
            $this->sendError();
        }
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        $endpoint = implode('/', $pages);
        try {
            $output = $this->http->post($this->url . $endpoint, $_POST, $this->options);
            $this->send($output);
        } catch (\Exception $e) {
            $this->sendError();
        }
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

    /**
     * Send reponse
     * @param string $output
     * @return void
     */
    private function send($output)
    {
        ob_end_clean();
        echo $output;
    }

    /**
     * Send fatal error response
     * @param string $msg
     * @return void
     */
    private function sendError($msg = '') {
        ob_end_clean();
        header('Fatal error', true, 500);
        echo $msg;
    }
}
