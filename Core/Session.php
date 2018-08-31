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
    private $session_name = 'minds';

    /** @var Config $config */
    private $config;

    public function __construct($force = null, $config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');

        session_set_save_handler(new core\Data\Sessions());

        ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // Persistent cookies - 30 days
        ini_set('session.cookie_secure', $this->config->disable_secure_cookies ? 'off' : 'on');
        ini_set('session.cookie_httponly', 'on');

        if (isset($_COOKIE['disable_cookies'])) {
            ini_set('session.use_cookies', false);
        }

        session_name('minds');
        session_start();

        session_cache_limiter('public');

        // Register a default PAM handler
        //@todo create an OOP pam handler
        \register_pam_handler('pam_auth_userpass');

        //there are occassions where we may want a session for loggedout users.
        if ($force) {
            $_SESSION['force'] = true;
        }

        // is there a remember me cookie
        //@todo remove this - now we have long lasting sessions
/*        if (isset($_COOKIE['mindsperm']) && !isset($_SESSION['user'])) {
            // we have a cookie, so try to log the user in
            $cookie = md5($_COOKIE['mindsperm']);
            if ($user = get_user_by_cookie($cookie)) {
                login($user);
            }
}*/

        // Generate a simple token (private from potentially public session id)
        if (!isset($_SESSION['__elgg_session'])) {
            $bytes = openssl_random_pseudo_bytes(128);
            $_SESSION['__elgg_session'] = hash('sha512', microtime() . $bytes);
        }

        $loggedInCookie = new Cookie();
        $loggedInCookie
            ->setName('loggedin')
            ->setExpire(time() + (60 * 60 * 24 * 30))
            ->setPath('/');
            
        if (isset($_SESSION['user'])) {
            $loggedInCookie->setValue(1);
            cache_entity($_SESSION['user']);
        } else {
            $loggedInCookie->setValue(0);
        }

        $loggedInCookie->create();


        if (!isset($_COOKIE['loggedin'])) {
            $loggedInCookie
                ->setValue(0)
                ->create();
            $_SESSION = array();
            unset($_COOKIE[session_name()]);
            session_destroy();
        }

        self::generateJWTCookie();

        header('X-Powered-By: Minds', true);

        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * Shutdown function to remove the session
     * @return null
     */
    public function shutdown()
    {
        //double check no loggedin cookie ensures session destroy
        if (!isset($_COOKIE['loggedin']) || $_COOKIE['loggedin'] == 0) {
            $_SESSION = [];
            if (session_status() == PHP_SESSION_ACTIVE) {
                session_destroy();
            }
        }

        if (isset($_COOKIE[session_name()]) && !isset($_SESSION['user']) && !isset($_SESSION['force'])) {
            //clear session from disk
            $params = session_get_cookie_params();
        /*	setcookie(session_name(), '', time()-60*60*24*30*12,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );*/
            $_SESSION = array();
            unset($_COOKIE[session_name()]);
            if (session_status() == PHP_SESSION_ACTIVE) {
                session_destroy();
            } 
        }
    }

    /**
     * Regenerates the session.
     * @param  bool  $new_id Regenerate the session ID too?
     * @param  User  $user   Current user override
     * @return null
     */
    public static function regenerate($new_id = true, $user = null)
    {
        $_SESSION['user'] = $user ?: new User($_SESSION['guid'], false);
        if ($new_id) {
            session_regenerate_id(true);
        }
    }

    /**
     * Create a JWT token for our web socket integration
     * @return null
     */
    public static function generateJWTCookie()
    {
        if (isset($_SESSION['user'])) {
            $jwt = \Firebase\JWT\JWT::encode([
              'guid' => (string) $_SESSION['user']->guid,
              'sessionId' => session_id()
            ], Config::_()->get('sockets-jwt-secret'));

            $cookie = new Cookie();
            $cookie
                ->setName('socket_jwt')
                ->setValue($jwt)
                ->setExpire(0)
                ->setPath('/')
                ->setDomain(Config::_()->get('sockets-jwt-domain') ?: 'minds.com')
                ->create();
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
        global $USERNAME_TO_GUID_MAP_CACHE;

        /**
         * The OAuth plugin, for example, might use this.
         */
        if ($user = \elgg_trigger_plugin_hook('logged_in_user', 'user')) {
            if (is_object($user) || is_array($user)) {
                return new User($user);
            }
        }

        if (isset($_SESSION['user'])) {
            //cache username
            $USERNAME_TO_GUID_MAP_CACHE[$_SESSION['username']] = $_SESSION['guid'];
            return new User($_SESSION['user']);
        }

        return null;
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
