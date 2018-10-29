<?php
/**
 * Minds OAuth Provider
 */
namespace Minds\Core\OAuth;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Di\Provider;

use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;

class OAuthProvider extends Provider
{

    public function register()
    {
        $this->di->bind('OAuth\Manager', function ($di) {
            return new Manager;
        }, ['useFactory'=>false]);

        // Authorization Server
        $this->di->bind('OAuth\Server\Authorization', function ($di) {
            $config = $di->get('Config');
            $clientRepository = $di->get('OAuth\Repositories\Client');
            $accessTokenRepository = $di->get('OAuth\Repositories\AccessToken');
            $scopeRepository = $di->get('OAuth\Repositories\Scope');

            $server = new AuthorizationServer(
                $clientRepository, // instance of ClientRepositoryInterface
                $accessTokenRepository, // instance of AccessTokenRepositoryInterface
                $scopeRepository, // instance of ScopeRepositoryInterface
                '/var/secure/oauth-priv.key',    // path to private key
                $config->oauth['encryption_key'] // encryption key
            );

            // Password grant
            $server->enableGrantType(
                $di->get('OAuth\Grants\Password'),
                new \DateInterval('PT72H') // expire access token after 72 hours
            );

            // Refresh grant
            $server->enableGrantType(
                $di->get('OAuth\Grants\RefreshToken'),
                new \DateInterval('PT72H') // expire access token after 72 hours
            );

            return $server;
        }, ['useFactory'=>true]);

        // Resource Server
        $this->di->bind('OAuth\Server\Resource', function ($di) {
            // Init our repositories
            $accessTokenRepository = $di->get('OAuth\Repositories\AccessToken');

            // Path to authorization server's public key
            $publicKeyPath = '/var/secure/oauth-pub.key';
                    
            // Setup the authorization server
            $server = new ResourceServer(
                $accessTokenRepository,
                $publicKeyPath
            );

            return $server;
        }, ['useFactory'=>true]);

        // Resource Server Middleware
        $this->di->bind('OAuth\Server\Resource\Middleware', function ($di) {
            return new ResourceServerMiddleware($di->get('OAuth\Server\Resource'));
        }, ['useFactory'=>true]);

        // Password grant
        $this->di->bind('OAuth\Grants\Password', function ($di) {
            $grant = new PasswordGrant(
                new Repositories\UserRepository(),           // instance of UserRepositoryInterface
                new Repositories\RefreshTokenRepository()    // instance of RefreshTokenRepositoryInterface
            );
            $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // expire after 1 month

            return $grant;
        }, ['useFactory'=>false]);

        // Refresh Token grant
        $this->di->bind('OAuth\Grants\RefreshToken', function ($di) {
            $refreshTokenRepository = $di->get('OAuth\Repositories\RefreshToken');
    
            $grant = new RefreshTokenGrant($refreshTokenRepository);
            $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // The refresh token will expire in 1 month
            
            return $grant;
        }, ['useFactory'=>false]);

        // Repositories
        $this->di->bind('OAuth\Repositories\RefreshToken', function ($di) {
            return new Repositories\RefreshTokenRepository;
        }, ['useFactory'=>true]);

        $this->di->bind('OAuth\Repositories\AccessToken', function ($di) {
            return new Repositories\AccessTokenRepository;
        }, ['useFactory'=>true]);

        $this->di->bind('OAuth\Repositories\User', function ($di) {
            return new Repositories\UserRepository;
        }, ['useFactory'=>true]);

        $this->di->bind('OAuth\Repositories\Client', function ($di) {
            return new Repositories\ClientRepository;
        }, ['useFactory'=>true]);

        $this->di->bind('OAuth\Repositories\Scope', function ($di) {
            return new Repositories\ScopeRepository;
        }, ['useFactory'=>true]);

    }

}
