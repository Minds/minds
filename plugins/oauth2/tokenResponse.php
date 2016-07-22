<?php

namespace minds\plugin\oauth2;

use OAuth2\Storage\AccessTokenInterface as AccessTokenStorageInterface;
use OAuth2\Storage\RefreshTokenInterface;

/**
 *
 * @author Brent Shaffer <bshafs at gmail dot com>
 */
class tokenResponse extends \OAuth2\ResponseType\AccessToken
{

    /**
     * Handle the creation of access token, also issue refresh token if supported / desirable.
     *
     * @param $client_id                client identifier related to the access token.
     * @param $user_id                  user ID associated with the access token
     * @param $scope                    OPTIONAL scopes to be stored in space-separated string.
     * @param bool $includeRefreshToken if true, a new refresh_token will be added to the response
     *
     * @see http://tools.ietf.org/html/rfc6749#section-5
     * @ingroup oauth2_section_5
     */
    public function createAccessToken($client_id, $user_id, $scope = null, $includeRefreshToken = true)
    {
        $token = array(
            "access_token" => $this->generateAccessToken(),
            "expires_in" => $this->config['access_lifetime'],
            "token_type" => $this->config['token_type'],
        "user_id" => $user_id,
            "scope" => $scope
        );

        $this->tokenStorage->setAccessToken($token["access_token"], $client_id, $user_id, $this->config['access_lifetime'] ? time() + $this->config['access_lifetime'] : null, $scope);

        /*
         * Issue a refresh token also, if we support them
         *
         * Refresh Tokens are considered supported if an instance of OAuth2\Storage\RefreshTokenInterface
         * is supplied in the constructor
         */
        if ($includeRefreshToken && $this->refreshStorage) {
            $token["refresh_token"] = $this->generateRefreshToken();
            $expires = 0;
            if ($this->config['refresh_token_lifetime'] > 0) {
                $expires = time() + $this->config['refresh_token_lifetime'];
            }
            $this->refreshStorage->setRefreshToken($token['refresh_token'], $client_id, $user_id, $expires, $scope);
        }

        return $token;
    }
}
