<?php

/**
 * Minds JSON-RPC Client
 *
 * @author emi
 */

namespace Minds\Core\Http\Curl\JsonRpc;

use Minds\Core\Http\Curl;

class Client extends Curl\Client
{
    static $version = '2.0';

    protected $id = 1;

    /**
     * Not used
     * @param $url
     * @param array $options
     * @return void
     * @throws \Exception
     */
    public function get($url, array $options = [])
    {
        throw new \Exception('Method not supported by JSON-RPC');
    }

    /**
     * Sends a request to an RPC-JSON server
     * @param $url
     * @param array $data
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function post($url, array $data = [], array $options = [])
    {
        $options = array_merge([
            'headers' => []
        ], $options);

        $options['headers'] = array_merge([
            'Content-Type: application/json'
        ], $options['headers']);

        $data = array_merge($data, [
            'version' => static::$version,
            'id' => $this->id++
        ]);

        if (!isset($data['method'])) {
            throw new \Exception('JSON-RPC required a method data field');
        }

        if (!isset($data['params'])) {
            $data['params'] = [];
        }

        $response = json_decode(parent::post($url, $data, $options), true);

        return $response;
    }

    /**
     * Not used
     * @param $url
     * @param array $data
     * @param array $options
     * @return void
     * @throws \Exception
     */
    public function put($url, array $data = [], array $options = [])
    {
        throw new \Exception('Method not supported by JSON-RPC');
    }

    /**
     * Not used
     * @param $url
     * @param array $data
     * @param array $options
     * @return void
     * @throws \Exception
     */
    public function delete($url, array $data = [], array $options = [])
    {
        throw new \Exception('Method not supported by JSON-RPC');
    }
}
