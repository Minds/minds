<?php
/**
 * XSRF Protections
 */
namespace Minds\Core\Security;

use Minds\Core;

class XSRF{

    public static function buildToken(){
        $user = Core\Session::getLoggedinUser();
        return md5($_SESSION['__elgg_session'] . rand(1000,9000));
    }

    public static function validateRequest(){
        if(!isset($_SERVER['HTTP_X_XSRF_TOKEN']))
            return false;

        if($_SERVER['HTTP_X_XSRF_TOKEN'] == $_COOKIE['XSRF-TOKEN'])
            return true;

        return false;
    }

    /**
     * Set the cookie
     * @return void
     */
    public static function setCookie(){
        $token = self::buildToken();
        setcookie('XSRF-TOKEN', $token, 0, '/');   
    }
}
