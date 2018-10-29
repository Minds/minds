<?php
/**
 * Minds OAuth ClientRepository
 */
namespace Minds\Core\OAuth\Repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Minds\Core\OAuth\Entities\ClientEntity;
use Minds\Core\Di\Di;
use Minds\Core\Security\XSRF;

class ClientRepository implements ClientRepositoryInterface
{

    /** @var Client $client */
    private $client;

    /** @var Config $config */
    private $config;

    public function __construct($client = null, $config = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Client');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * {@inheritdoc}
     * TODO: Implement clients for 3rd party apps
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $clients = [
            'mobile' => [
                'secret'          => $this->config->get('oauth')['clients']['mobile']['secret'],
                'name'            => 'Mobile',
                'redirect_uri'    => '',
                'is_confidential' => $grantType === 'password' || $grantType === 'refresh_token' ? false : true,
            ],
        ];

        // Check if client is registered
        if (array_key_exists($clientIdentifier, $clients) === false) {
            return;
        }

        if (
            $mustValidateSecret === true
            && $clients[$clientIdentifier]['is_confidential'] === true
            && $clients[$clientIdentifier]['secret'] !== $clientSecret
        ) {
            return;
        }

        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName($clients[$clientIdentifier]['name']);
        $client->setRedirectUri($clients[$clientIdentifier]['redirect_uri']);

        return $client;
    }

}
