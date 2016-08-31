<?php
namespace Minds\Core\ThirdPartyNetworks\Networks;

use Minds\Core;
use Minds\Core\Di\Di;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter implements NetworkInterface
{
    protected $config;
    protected $callbackUrl;

    public function __construct()
    {
        $this->config = Di::_()->get('Config');

        $this->callbackUrl = $this->config->get('site_url') . 'api/v1/thirdpartynetworks/twitter/login-callback';
    }

    /**
     * Set and save the api credentials
     * @param array $credentials
     * @return $this
     */
    public function setApiCredentials($credentials = [])
    {
        Di::_()->get('ThirdPartyNetworks\Credentials')
            ->set(Core\Session::getLoggedInUser()->guid, 'twitter', [
                'oauth_token' => $credentials['oauth_token'],
                'oauth_token_secret' => $credentials['oauth_token_secret'],
                'uuid' => $credentials['user_id'],
                'username' => $credentials['screen_name'],
                'expires' => $credentials['x_auth_expires'],
            ]);

        return $this;
    }

    /**
     * Set and save the temporary API credentials (for OAuth flow)
     * @param array $credentials
     * @return $this
     */
    public function setTempApiCredentials($credentials = [])
    {
        Di::_()->get('ThirdPartyNetworks\Credentials')
            ->set(Core\Session::getLoggedInUser()->guid, 'twitter', [
                'oauth_token' => $credentials['oauth_token'],
                'oauth_token_secret' => $credentials['oauth_token_secret']
            ]);

        return $this;
    }

    /**
     * Drop the api credentials
     * @param array $credentials
     * @return $this
     */
    public function dropApiCredentials()
    {
        Di::_()->get('ThirdPartyNetworks\Credentials')
            ->drop(Core\Session::getLoggedInUser()->guid, 'twitter', [
                'oauth_token',
                'oauth_token_secret',
                'uuid',
                'username',
                'expires',
            ]);

        return $this;
    }

    /**
     * Return api credentials
     * @return array
     */
    public function getApiCredentials()
    {
        $this->credentials = Di::_()->get('ThirdPartyNetworks\Credentials')
            ->get(Core\Session::getLoggedInUser()->guid, 'twitter', [
                'oauth_token',
                'oauth_token_secret',
                'uuid',
                'username',
                'expires',
            ]);

        return $this;
    }

    /**
     * Creates a TwitterOAuth connection with current user's credentials
     * @return TwitterOAuth
     */
    public function connect()
    {
        if (!$this->credentials) {
            return false;
        }

        $config = $this->config->get('twitter');

        $connection = new TwitterOAuth(
            $config['api_key'],
            $config['api_secret'],
            $this->credentials['oauth_token'],
            $this->credentials['oauth_token_secret']
        );
        $connection->setTimeouts(10, 15);

        return $connection;
    }

    /**
     * Create a post
     * @param object $entity
     * @return $this
     */
    public function post($entity)
    {
        if ($entity->remind_object) {
            $entity = new Entities\Activity($entity->remind_object);
        }

        $tweet = '';

        if ($entity->title) {
            $tweet = $entity->title;
        } elseif ($entity->message) {
            $tweet = $entity->message;
        }

        $url = $this->config->get('site_url');

        if ($entity->custom_type == 'video') {
            $url .= 'archive/view/' . $entity->guid;
        } else {
            $url .= 'newsfeed/' . $entity->guid;
        }

        $urlLength = 139 - strlen($url);

        if (strlen($tweet) > $urlLength) {
            $tweet = substr($tweet, 0, $urlLength - 1) . '…';
        }

        $tweet = trim($tweet . ' ' . $url);

        $connection = $this->connect();

        if (!$connection) {
            return false;
        }

        $result = $connection->post('statuses/update', ['status' => $tweet]);

        return $result->id;
    }

    /**
     * Schedule a post
     * @param int $timestamp
     * @return $this
     */
    public function schedule($timestamp)
    {
        // NYI
    }

    /**
     * Export API information for end-user displaying
     * @return array
     */
    public function export()
    {
        $connected = isset($this->credentials['oauth_token']) && $this->credentials['oauth_token'];

        return [
            'connected' => $connected,
            'username' => $connected ? $this->credentials['username'] : ''
        ];
    }

    /**
     * Fetches access tokens and builds authorize URL
     * @return string
     */
    public function authorized($request)
    {
        $config = $this->config->get('twitter');

        if ($this->credentials['oauth_token'] !== $request['oauth_token']) {
            return false;
        }

        $connection = $this->connect();

        $credentials = $connection->oauth('oauth/access_token', ['oauth_verifier' => $request['oauth_verifier'] ]);

        $this->setApiCredentials($credentials);

        return true;
    }
    
    /**
     * Fetches access tokens and builds authorize URL
     * @return string
     */
    public function buildAuthorizeUrl()
    {
        $config = $this->config->get('twitter');

        $connection = new TwitterOAuth($config['api_key'], $config['api_secret']);
        $connection->setTimeouts(10, 15);

        $request_token = $connection->oauth('oauth/request_token', [ 'oauth_callback' => $this->callbackUrl ]);

        $this->setTempApiCredentials($request_token);

        return $connection->url('oauth/authorize', [ 'oauth_token' => $request_token['oauth_token'] ]);
    }
}
