<?php
namespace Minds\Core;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Common\Cookie;
use Minds\Entities\User;

/**
 * Minds Session Manager
 * @todo Session Name should be configurable
 */
class Session extends base
{
    private static $user;

    private $session_name = 'minds';

    /** @var Config $config */
    private $config;

    public function __construct($force = null, $config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        header('X-Powered-By: Minds', true);
    }

    /**
     * Regenerates the session.
     * @param  bool  $new_id Regenerate the session ID too?
     * @param  User  $user   Current user override
     * @return null
     */
    public static function regenerate($new_id = true, $user = null)
    {
        error_log('DEPRECATED: session->regenerate');
    }

    /**
     * Create a JWT token for our web socket integration
     * @param User $user
     * @return null
     */
    public static function generateJWTCookie($session)
    {
        $expires = time() + (60 * 60); // expire in 1 hour
        $jwt = \Firebase\JWT\JWT::encode([
            'guid' => (string) $session->getUserGuid(),
            'expires' => $expires,
            'sessionId' => $session->getId(),
        ], Config::_()->get('sockets-jwt-secret'));

        $cookie = new Cookie();
        $cookie
            ->setName('socket_jwt')
            ->setValue($jwt)
            ->setExpire($expires)
            ->setPath('/')
            ->setDomain(Config::_()->get('sockets-jwt-domain') ?: 'minds.com')
            ->create();
    }

    /**
     * Construct the user via the OAuth middleware
     * @param $server
     * @return void
     */
    public static function withRouterRequest(&$request, &$response)
    {
        try {
            $server = Di::_()->get('OAuth\Server\Resource');
            $request = $server->validateAuthenticatedRequest($request);
            $user_guid = $request->getAttribute('oauth_user_id');
            static::setUserByGuid($user_guid); 
        } catch (\Exception $e) {
           // var_dump($e);
        }
    }

    /**
     * Construct the user manually by guid
     * @param $user
     * @return void
     */
    public static function setUserByGuid($user_guid)
    {
        $user = new User($user_guid);
        static::setUser($user);
    }

    /**
     * Construct the user manually
     * @param $user
     * @return void
     */
    public static function setUser($user)
    {
        static::$user = $user;
        if (!$user 
            || !static::$user->username
            || static::$user->isBanned()
            || !static::$user->isEnabled()
        ) {
            static::$user = null; //bad user
        }
    }


    /**
     * Check if there's an user logged in
     */
    public static function isLoggedin()
    {
        $user = self::getLoggedinUser();

        if ((isset($user)) && ($user instanceof \ElggUser || $user instanceof User) && $user->guid) {
            return true;
        }

        return false;
    }

    /**
     * Check if the current user is an administrator
     */
    public static function isAdmin()
    {
        if (!self::isLoggedin()) {
            return false;
        }

        $user = self::getLoggedinUser();
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Get the logged in user's entity
     */
    public static function getLoggedinUser()
    {
        return static::$user;
    }

    /**
     * Get the logged in user's entity GUID
     */
    public static function getLoggedInUserGuid()
    {
        if ($user = self::getLoggedinUser()) {
            return $user->guid;
        }

        return false;
    }
}
