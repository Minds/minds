<?php
/**
 * Minds OAuth AccessTokenRepository.
 */

namespace Minds\Core\OAuth\Repositories;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Minds\Core\OAuth\Entities\AccessTokenEntity;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Cassandra\Set;
use Cassandra\Type;
use Cassandra\Timestamp;
use Cassandra\Varint;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /** @var Client $client */
    private $client;

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $scopes = new Set(Type::text());
        foreach ($accessTokenEntity->getScopes() as $scope) {
            $scopes->add($scope->getIdentifier());
        }
        $prepared = new Prepared();
        $prepared->query('
            INSERT INTO oauth_access_tokens (token_id, client_id, user_id, expires, last_active, scopes)
            VALUES (?, ?, ?, ?, ?, ?)
            ', [
                $accessTokenEntity->getIdentifier(),
                $accessTokenEntity->getClient()->getIdentifier(),
                new Varint($accessTokenEntity->getUserIdentifier()),
                new Timestamp($accessTokenEntity->getExpiryDateTime()->getTimestamp()),
                new Timestamp(time()), //now
                $scopes,
            ]);

        $this->client->request($prepared);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $prepared = new Prepared();
        $prepared->query('DELETE FROM oauth_access_tokens where token_id = ?', [
            $tokenId,
        ]);
        $this->client->request($prepared);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $prepared = new Prepared();
        $prepared->query('SELECT * FROM oauth_access_tokens where token_id = ?', [
            $tokenId,
        ]);
        $this->client->request($prepared);
        $response = $this->client->request($prepared);

        if (!$response || $response[0]['token_id'] != $tokenId) {
            return true; // Access token could not be found
        }

        return false; // Access token still exists
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }
}
