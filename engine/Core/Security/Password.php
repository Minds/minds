<?php
/**
 * Password security functions
 */
namespace Minds\Core\Security;

use Minds\Core;
use Minds\Entities;

class Password {

    /**
     * Check if a password is valid
     * @param mixed $user
     * @param string $password
     * @return boolean
     */
    public function check($user, $password){
      if(is_numeric($user) || is_string($user))
        $user = new Entities\User($user);

      $algo = 'sha256';
    	if(strlen($user->password) == 32) //legacy users might still be using md5
    		$algo = 'md5';

    	if ($user->password !== self::generate($user, $password, $algo)) {
        log_login_failure($user->guid);
        return false;
      }

      return true;
    }

    /**
     * Generate a password
     * @param entities\User $user
     * @param string $password
     * @param string $algo (optional)
     * @return string
     */
    public static function generate($user, $password, $algo = "sha256"){
      if($algo == 'md5')
    			return md5($password . $user->salt);
    	return hash('sha256', $password . $user->salt);
    }

    /**
     * Return a salt Value
     * @return string
     */
    public static function salt(){
      return substr(hash('sha256', microtime() . rand()), 0, 8);
    }
}
