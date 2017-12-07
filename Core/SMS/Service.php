<?php
/**
 * Minds SMS Service
 */

namespace Minds\Core\SMS;

use Minds\Core\Config;
use Minds\Core\Di\Di;

class Service
{
    protected $client;

    public function __construct($client = null, $config = null) {
        $awsConfig = $config ? $config : Di::_()->get('Config')->aws;

        $opts = [
            'version' => 'latest',
            'region' => $awsConfig['region']
        ];

        if (!isset($awsConfig['useRoles']) || !$awsConfig['useRoles']) {
            $opts['credentials'] = [
                'key' => $awsConfig['key'],
                'secret' => $awsConfig['secret'],
            ];
        }

        $this->client = $client? $client : new \Aws\Sns\SnsClient($opts);
    }
    /**
     * Send an sms
     */
    public function send($number, $message)
    {
        $result = null;

        if (strpos($number, '1', 0) === 0) {
            $number = "+$number";
        }

        if (strpos($number, '+', 0) === FALSE) {
            $number = "+1 $number";
        }

        $args = [
            "SenderID" => "Minds",
            "SMSType" => "Transactional",
            "Message" => $message,
            "PhoneNumber" => $number
        ];
        $result = $this->client->publish($args);

        return $result && $result['MessageId'];
    }
}
