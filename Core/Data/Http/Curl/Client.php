<?php
namespace Minds\Core\Data\Http\Curl;

class Client
{
    public function get($url, array $options = [])
    {
        $options = array_merge([ 'method' => 'get', 'url' => $url ], $options);
        return $this->request($options);
    }

    public function post($url, array $data = [], array $options = [])
    {
        $options = array_merge([ 'method' => 'post', 'url' => $url, 'data' => $data ], $options);
        return $this->request($options);
    }

    public function put($url, array $data = [], array $options = [])
    {
        $options = array_merge([ 'method' => 'put', 'url' => $url, 'data' => $data ], $options);
        return $this->request($options);
    }

    public function delete($url, array $data = [], array $options = [])
    {
        $options = array_merge([ 'method' => 'delete', 'url' => $url, 'data' => $data ], $options);
        return $this->request($options);
    }

    public function request($options)
    {
        $options = array_merge([
            'method' => 'get',
            'url' => '',
            'data' => [],
            'headers' => [],
            'curl' => [],
        ], $options);

        $headers = [];

        if (!$options['url']) {
            return false;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $options['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $validMethods = ['get', 'post', 'put', 'delete', 'options', 'head'];

        if (in_array($options['method'], $validMethods)) {
            switch ($options['method']) {
                case 'get':
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    break;
                case 'post':
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $options['data']);
                    break;
                case 'options':
                case 'head':
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($options['method']));
                    break;
                case 'put':
                case 'delete':
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($options['method']));
                    $headers = array_merge($headers, [ 'X-HTTP-Method-Override: ' . strtoupper($options['method']) ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $options['data']);
                    break;
            }
        }

        if ($options['headers']) {
            $headers = array_merge($headers, $options['headers']);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($options['curl']) {
            curl_setopt_array($ch, $options['curl']);
        }

        $response = curl_exec($ch);
        $errorNumber = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errorNumber) {
            throw new \Exception($error, $errorNumber);
        }

        return $response;
    }
}
