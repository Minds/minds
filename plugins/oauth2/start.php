<?php
/**
 * Minds Channel Profiles
 *
 * @package channel
 */
namespace minds\plugin\oauth2;

use Minds\Core;

class start extends \ElggPlugin
{

    /**
     * Initialize the oauth2 plugin
     */
    public function init()
    {

        /**
         * @todo update this once minds gets a better PAM system
         */
        register_pam_handler("\\minds\\plugin\\oauth2\\start::pam", 'sufficient', 'user');
        register_pam_handler("\\minds\\plugin\\oauth2\\start::pam", 'sufficient', 'api');
        
        $user_pam = new \ElggPAM('user');
        $user_auth_result = $user_pam->authenticate();
    
        elgg_register_plugin_hook_handler('logged_in_user', 'user', [$this, 'loggedInUserEntity']);
        
        
        core\Router::registerRoutes(array(
            '/oauth2/token' => "\\minds\\plugin\\oauth2\\pages\\token",
            '/oauth2/grant' => "\\minds\\plugin\\oauth2\\pages\\token", //this is soon to be deprecated
            '/oauth2/authorize' => "\\minds\\plugin\\oauth2\\pages\\authorize",
            '/oauth2/applications' => "\\minds\\plugin\\oauth2\\pages\\applications",
        ));

        register_shutdown_function([$this, 'onShutdown']);
    }

    public function onShutdown()
    {
        $storage = new storage();
        $server = new \OAuth2\Server($storage);

        if (!$server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            return false;
        }

        static::ensureNoSession();
    }

    public function loggedInUserEntity()
    {
        $bearer = new \OAuth2\TokenType\Bearer();
        $access_token = $bearer->getAccessTokenParameter(\OAuth2\Request::createFromGlobals(), new \minds\plugin\oauth2\response());
 
        if (isset($_SESSION['user'])) {
            return false; // already logged in, let someone else figure this out
        }

        // Get the token data
        $token = get_input('access_token', $access_token);

        if ($token) {

            static::ensureNoSession();

            static $OAUTH2_LOGGED_IN;
            if ($OAUTH2_LOGGED_IN) {
                return $OAUTH2_LOGGED_IN;
            }
        
            $storage = new storage();
            // Create a server instance
            $server = new \OAuth2\Server($storage);
            // Get the token data
            $token = $storage->getAccessToken($token);
            $user = new \ElggUser($token['user_id']);
            $OAUTH2_LOGGED_IN = $user;

            static::ensureNoSession();
            return $user;
        }
        
        return false;
    }
    
    /**
     * PAM: Confirm that the call includes an access token
     *
     * @return bool
     */
    public static function pam($credentials = null)
    {
        $storage = new storage();
        $server = new \OAuth2\Server($storage);

        if (!$server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            return false;
        }
        
        //can not have a session too
        static::ensureNoSession();

        $bearer = new \OAuth2\TokenType\Bearer();
        $access_token = $bearer->getAccessTokenParameter(\OAuth2\Request::createFromGlobals(), new \minds\plugin\oauth2\response());

        // Get the token data
        $token = $storage->getAccessToken(get_input('access_token', $access_token));

        static $OAUTH2_LOGGED_IN;
        $user = new \ElggUser($token['user_id']);
        if ($user->enabled != "yes") {
            $user->enable();
        }

        if ($user->guid && !$user->isBanned()) {
            $OAUTH2_LOGGED_IN = $user;
            static::ensureNoSession();
            return true;
        }

        static::ensureNoSession();
  
        return false;
    }

    /**
     * A sanity/safe check to ensure that OAuth2 can never be confused with
     * sessions
     */
    private static function ensureNoSession()
    {
        $_SESSION = [];
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
    
    public static function generateSecret()
    {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
/*function oauth2_page_handler($page) {

    // Load our library methods
    elgg_load_library('oauth2');

    // Load the javascript
    elgg_load_js('oauth2');

    $base = elgg_get_plugins_path() . 'oauth2';

    $pages = $base . '/pages/oauth2';

    switch ($page[0]) {

        case 'token':
            require $pages . "/token.php";
            break;

        case 'authorize':
            require $pages . "/authorize.php";
            break;

        case 'grant':
            oauth2_grant();
            break;

        case 'get_user':
            oauth2_get_user_by_access_token();
            break;

        case 'regenerate':
            echo oauth2_generate_client_secret();
            break;

        case 'add':
        case 'edit':
        case 'register':
            require $pages . "/register.php";
            break;
        case 'sso':
            oauth2_SSO();
            break;

        case 'applications':
        default:
            require $pages . "/applications.php";
            break;

    }

    return true;
}
*/
/**
 * Auto login if a cookie is found
 */
/*function oauth2_SSO(){
    // Load our oauth2 library
    elgg_load_library('oauth2');
    $storage = new ElggOAuth2DataStore();
    $ia = elgg_set_ignore_access();
    $token = $storage->getAccessToken(get_input('access_token'));
    elgg_set_ignore_access($ia);
    if(!$token['user_id']){
        header('Location: ' . get_input('redirect_uri', $_SERVER['HTTP_REFERRER']));

    }
        $user = get_entity($token['user_id']);
    if(!$user)
        header('Location: ' . get_input('redirect_uri', $_SERVER['HTTP_REFERRER']));

    login($user);
    header('Location: ' . get_input('redirect_uri', $_SERVER['HTTP_REFERRER']));

}*/
