<?php
namespace Minds\Core\Data\Http\Curl\Json;

use Minds\Core\Data\Http\Curl;

class Client extends Curl\Client
{
    public function get($url, array $options = [])
    {
        $response = parent::get($url, $options);
        return json_decode($response, true);
    }

    public function post($url, array $data = [], array $options = [])
    {
        $response = parent::post($url, $data, $options);
        return json_decode($response, true);
    }

    public function put($url, array $data = [], array $options = [])
    {
        $response = parent::put($url, $data, $options);
        return json_decode($response, true);
    }

    public function delete($url, array $data = [], array $options = [])
    {
        $response = parent::delete($url, $data, $options);
        return json_decode($response, true);
    }
}
