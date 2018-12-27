<?php
/**
 * Minds SMS Service via Twilio
 */

namespace Minds\Core\SMS\Services;

use Minds\Core\Di\Di;
use Minds\Core\SMS\SMSServiceInterface;
use Twilio\Rest\Client as TwilioClient;

class Twilio implements SMSServiceInterface
{
    /** @var TwilioClient */
    protected $client;
    /** @var array */
    protected $config;
    protected $from;

    public function __construct($client = null, $config = null)
    {
        $this->config = $config ? $config : Di::_()->get('Config')->get('twilio');

        $AccountSid = $this->config['account_sid'];
        $AuthToken = $this->config['auth_token'];
        $this->client = $client ? $client : new TwilioClient($AccountSid, $AuthToken);
    }

    /**
     * Verifies the number isn't a voip line
     * @param $number
     * @return boolean
     */
    public function verify($number)
    {

        try {
            $phone_number = $this->client->lookups->v1->phoneNumbers($number)
                ->fetch(array("type" => "carrier"));

            return $phone_number->carrier['type'] !== 'voip';
        } catch (\Exception $e) {
            error_log("[guard] Twilio error: {$e->getMessage()}");
        }
        return false;
    }

    /**
     * Send an sms
     */
    public function send($number, $message)
    {
        $result = null;

        try {
            $result = $this->client->messages->create(
                $number, [
                'from' => $this->config['from'],
                'body' => $message,
            ]);
        } catch (\Exception $e) {
            error_log("[guard] Twilio error: {$e->getMessage()}");
        }

        return $result ? $result->sid : false;
    }
}
