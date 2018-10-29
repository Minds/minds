<?php
/**
 * Minds OAuth RefreshTokenRepository
 */
namespace Minds\Core\OAuth\Repositories;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Minds\Core\OAuth\Entities\RefreshTokenEntity;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Cassandra\Timestamp;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
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
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $prepared = new Prepared;
        $prepared->query("
            INSERT INTO oauth_refresh_tokens (token_id, access_token_id, expires)
            VALUES (?, ?, ?)
            ", [
                $refreshTokenEntity->getIdentifier(),
                $refreshTokenEntity->getAccessToken()->getIdentifier(),
                new Timestamp($refreshTokenEntity->getExpiryDateTime()->getTimestamp()),
            ]);
        $this->client->request($prepared);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $prepared = new Prepared;
        $prepared->query("DELETE FROM oauth_refresh_tokens where token_id = ?", [
            $tokenId
        ]);
        $this->client->request($prepared);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $prepared = new Prepared;
        $prepared->query("SELECT * FROM oauth_refresh_tokens where token_id = ?", [
            $tokenId
        ]);
        $this->client->request($prepared);
        $response = $this->client->request($prepared);

        if (!$response) {
            return true; // Refresh token could not be found
        }

        return false; // Refresh token still exists
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
    }
}
