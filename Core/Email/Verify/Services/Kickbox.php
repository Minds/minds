<?php
namespace Minds\Core\Email\Verify\Services;

use Kickbox\Client as KickboxClient;
use Minds\Core\Config;

class Kickbox
{

    /** @var KickboxClient $client */
    private $client;

    public function __construct($client = null, $config = null)
    {
        $config = $config ?: Config::_();
        $this->client  = $client ?: (new KickboxClient($config->get('kickbox_secret')))
                                        ->kickbox();
    }

    /**
     * Verify if an email is valid
     * @param string $email
     * @return bool
     */
    public function verify($email)
    {
        $response =  $this->client->verify($email);
        return !($response->body['result'] == 'undeliverable');
    }

}
