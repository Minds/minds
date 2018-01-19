<?php
/**
 * Minds SMS Service via Twilio
 */

namespace Minds\Core\SMS\Services;

use Minds\Core\Di\Di;
use Minds\Core\SMS\SMSServiceInterface;

class Twilio implements SMSServiceInterface
{
    /** @var \Services_Twilio */
    protected $client;
    /** @var array */
    protected $config;
    protected $from;

    public function __construct($client = null, $config = null)
    {
        $this->config = $config ? $config : Di::_()->get('Config')->get('twilio');

        $AccountSid = $this->config['account_sid'];
        $AuthToken = $this->config['auth_token'];
        $this->client = $client ? $client : new \Services_Twilio($AccountSid, $AuthToken);
    }

    /**
     * Send an sms
     */
    public function send($number, $message)
    {

        $result = null;

        try {

            $result = $this->client->account->messages->create([
                'To' => $number,
                'From' => $this->config['from'],
                'Body' => $message,
            ]);
        } catch (\Exception $e) {
            error_log("[guard] Twilio error: {$e->getMessage()}");
        }

        return $result ? $result->sid : false;
    }
}