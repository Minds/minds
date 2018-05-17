<?php
/**
 * XSRF Protections
 */
namespace Minds\Core\Security;

use Minds\Core;
use Minds\Common\Cookie;

class XSRF
{
    public static function buildToken()
    {
        $bytes = openssl_random_pseudo_bytes(128);
        return hash('sha512', $_SESSION['__elgg_session'] . $bytes);
    }

    public static function validateRequest()
    {
        if (!isset($_SERVER['HTTP_X_XSRF_TOKEN'])) {
            return false;
        }

        if ($_SERVER['HTTP_X_XSRF_TOKEN'] == $_COOKIE['XSRF-TOKEN']) {
            return true;
        }

        return false;
    }

    /**
     * Set the cookie
     * @return void
     */
    public static function setCookie($force = false)
    {
        if (!$force && isset($_COOKIE['XSRF-TOKEN'])) {
            return;
        }
        $token = self::buildToken();

        $cookie = new Cookie();
        $cookie
            ->setName('XSRF-TOKEN')
            ->setValue($token)
            ->setExpire(0)
            ->setPath('/')
            ->setHttpOnly(false) //must be able to read in JS
            ->create();
    }
}
