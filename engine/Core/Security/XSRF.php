<?php
/**
 * XSRF Protections
 */
namespace Minds\Core\Security;

use Minds\Core;

class XSRF{
 
    public static function buildToken(){
        $user = Core\session::getLoggedinUser();
        return md5($_SESSION['__elgg_session'] . rand(1000,9000));
    }
    
    public static function validateRequest($payload){
        
    }
    
    /**
     * Set the cookie
     * @return void
     */
    public static function setCookie(){
        $token = self::buildToken();
        setcookie('XSRF-TOKEN', $token, time() + 360, '/');   
    }   
}   