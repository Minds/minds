<?php
namespace Minds\Core\Email\Verify\Services;

use Kickbox\Client as KickboxClient;
use Minds\Core\Config;

class Kickbox
{
    /** @var KickboxClient $client */
    private $client;

    /** @var Config $config */
    private $config;

    public function __construct($client = null, $config = null)
    {
        $this->config = $config ?: Config::_();
        $this->client  = $client ?: (new KickboxClient($this->config->get('kickbox_secret')))
                                        ->kickbox();
    }

    /**
     * Verify if an email is valid
     * @param string $email
     * @return bool
     */
    public function verify($email)
    {
        if (!$this->config->get('kickbox_secret'))
            return true;
        $response =  $this->client->verify($email);
        return !($response->body['result'] == 'undeliverable');
    }

}
